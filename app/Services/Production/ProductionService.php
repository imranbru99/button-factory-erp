<?php

namespace App\Services\Production;

use App\Models\Bom;
use App\Models\Employee;
use App\Models\Formula;
use App\Models\Machine;
use App\Models\MachineRuntime;
use App\Models\ProcessTemplate;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SalesOrderLine;
use App\Models\Warehouse;
use App\Models\WorkOrder;
use App\Models\WorkOrderStage;
use App\Services\Automation\IssueAutomationEngine;
use App\Services\Finance\CostingService;
use App\Services\Inventory\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductionService
{
    public function __construct(private readonly StockService $stock) {}

    public function open(Product $product, float $qty, string $strategy = 'mts', ?SalesOrderLine $line = null, bool $rework = false, ?WorkOrder $parent = null): WorkOrder
    {
        $bom = $product->activeBom();
        $formula = $product->activeFormula()
            ?? Formula::query()->where('status', 'active')->where('code', 'F-'.$product->color)->first();

        $order = WorkOrder::query()->create([
            'product_id' => $product->id,
            'sales_order_line_id' => $line?->id,
            'bom_id' => $bom?->id,
            'formula_id' => $formula?->id,
            'parent_work_order_id' => $parent?->id,
            'is_rework' => $rework,
            'qty_planned' => $qty,
            'status' => 'planned',
            'strategy' => $strategy,
            'priority' => $rework ? 1 : 3,
            'notes' => $rework ? 'Rework created from scrap or a non-conformance.' : null,
        ]);

        $this->attachStages($order);
        $this->schedule($order);

        return $order->load('stages');
    }

    public function release(WorkOrder $order): WorkOrder
    {
        if ($order->stages()->doesntExist()) {
            $this->attachStages($order);
        }

        $order->update([
            'status' => 'released',
            'released_at' => now(),
        ]);

        $first = $order->stages()->orderBy('sequence')->first();
        if ($first && (float) $first->qty_in <= 0) {
            $first->update(['qty_in' => $order->qty_planned]);
        }

        return $order->refresh();
    }

    public function start(WorkOrderStage $stage, ?Employee $operator = null, ?Machine $machine = null): WorkOrderStage
    {
        if (! in_array($stage->status, ['pending', 'paused'], true)) {
            throw ValidationException::withMessages(['stage' => 'This stage is not ready to start.']);
        }

        if ($stage->sequence > 1) {
            $previous = $stage->workOrder->stages()->where('sequence', $stage->sequence - 1)->first();
            if ($previous && $previous->status !== 'completed') {
                throw ValidationException::withMessages(['stage' => 'The previous stage is still open.']);
            }
        }

        $stage->update([
            'status' => 'running',
            'operator_id' => $operator?->id ?? $stage->operator_id,
            'machine_id' => $machine?->id ?? $stage->machine_id,
            'started_at' => $stage->started_at ?: now(),
            'qty_in' => (float) $stage->qty_in > 0 ? $stage->qty_in : $stage->workOrder->qty_planned,
        ]);

        $stage->workOrder->update(['status' => 'in_progress']);

        if ($machine) {
            $machine->update(['status' => 'running']);
            MachineRuntime::query()->create([
                'machine_id' => $machine->id,
                'work_order_id' => $stage->work_order_id,
                'started_at' => now(),
                'planned_minutes' => $stage->expected_minutes,
                'total_qty' => $stage->qty_in,
            ]);
        }

        return $stage->refresh();
    }

    public function complete(WorkOrderStage $stage, float $qtyOut, float $qtyScrap): WorkOrderStage
    {
        if ($stage->status !== 'running') {
            throw ValidationException::withMessages(['stage' => 'Start the stage before completing it.']);
        }

        if ($qtyOut < 0 || $qtyScrap < 0) {
            throw ValidationException::withMessages(['qty' => 'Quantities cannot be negative.']);
        }

        return DB::transaction(function () use ($stage, $qtyOut, $qtyScrap) {
            $stage->update([
                'status' => 'completed',
                'ended_at' => now(),
                'qty_out' => $qtyOut,
                'qty_scrap' => $qtyScrap,
            ]);

            $this->consume($stage);
            $this->closeRuntime($stage, $qtyOut, $qtyOut + $qtyScrap);

            $order = $stage->workOrder()->lockForUpdate()->first();
            $order->qty_scrapped = (float) $order->stages()->sum('qty_scrap');

            $next = $order->stages()->where('sequence', $stage->sequence + 1)->first();
            if ($next) {
                $next->update(['qty_in' => $qtyOut, 'status' => $next->status === 'blocked' ? 'blocked' : 'pending']);
            } else {
                $order->qty_completed = $qtyOut;
                $order->status = 'completed';
                $order->completed_at = now();
                $this->receiveFinished($order, $qtyOut);
                app(CostingService::class)->calculate($order);
            }

            $order->save();
            app(IssueAutomationEngine::class)->afterStage($stage->refresh());

            return $stage->refresh();
        });
    }

    public function attachStages(WorkOrder $order): void
    {
        $family = $order->product->button_family;
        $template = ProcessTemplate::query()->where('button_family', $family)->with('stages')->first()
            ?? ProcessTemplate::query()->where('button_family', 'polyester_sheet')->with('stages')->first();

        $stages = $template?->stages ?? collect();
        if ($family === 'shell') {
            $template = ProcessTemplate::query()->where('button_family', 'shell')->with('stages')->first() ?? $template;
            $stages = $template?->stages ?? $stages;
        }

        foreach ($stages->sortBy('sequence') as $index => $definition) {
            $minutes = ((float) $definition->minutes_per_thousand) * ((float) $order->qty_planned / 1000);
            WorkOrderStage::query()->create([
                'work_order_id' => $order->id,
                'code' => $definition->code,
                'name' => $definition->name,
                'sequence' => $definition->sequence ?: ($index + 1),
                'status' => 'pending',
                'expected_minutes' => round($minutes, 2),
                'qty_in' => $index === 0 ? $order->qty_planned : 0,
            ]);
        }
    }

    public function schedule(WorkOrder $order): void
    {
        $cursor = now()->addHour();
        $order->load('stages');

        foreach ($order->stages as $stage) {
            $stage->notes = trim(($stage->notes ? $stage->notes.' ' : '').'Planned start '.$cursor->toDateTimeString());
            $stage->save();
            $cursor = $cursor->copy()->addMinutes((int) ceil((float) $stage->expected_minutes));
        }

        $order->update([
            'scheduled_start' => now()->addHour(),
            'scheduled_end' => $cursor,
        ]);
    }

    private function consume(WorkOrderStage $stage): void
    {
        $order = $stage->workOrder()->with('bom.items.material')->first();
        $bom = $order->bom;
        if (! $bom instanceof Bom) {
            return;
        }

        $pieces = (float) $stage->qty_out + (float) $stage->qty_scrap;
        $thousands = $pieces / 1000;

        foreach ($bom->items as $item) {
            if ($item->component_type !== 'material' || ! $item->material) {
                continue;
            }

            $at = $item->consume_at_stage ?: ($order->product->button_family === 'shell' ? 'shell_selection' : 'mixing');
            if ($at !== $stage->code) {
                continue;
            }

            $qty = (float) $item->quantity * (1 + (float) $item->scrap_factor) * $thousands;
            if ($qty <= 0) {
                continue;
            }

            $this->stock->issue($item->material, $qty, null, $order, 'production_consume');
        }
    }

    private function receiveFinished(WorkOrder $order, float $qty): void
    {
        $warehouse = Warehouse::query()->where('kind', 'finished')->first();
        if (! $warehouse || $qty <= 0) {
            return;
        }

        $stock = ProductStock::query()->firstOrCreate(
            ['product_id' => $order->product_id, 'warehouse_id' => $warehouse->id],
            ['qty' => 0],
        );
        $stock->qty = (float) $stock->qty + $qty;
        $stock->save();
    }

    private function closeRuntime(WorkOrderStage $stage, float $good, float $total): void
    {
        if (! $stage->machine_id) {
            return;
        }

        $runtime = MachineRuntime::query()
            ->where('machine_id', $stage->machine_id)
            ->where('work_order_id', $stage->work_order_id)
            ->whereNull('ended_at')
            ->latest('id')
            ->first();

        $runtime?->update([
            'ended_at' => now(),
            'good_qty' => $good,
            'total_qty' => $total,
        ]);

        $stage->machine?->update(['status' => 'idle']);
    }
}
