<?php

namespace App\Services\Production;

use App\Models\Material;
use App\Models\ProductStock;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionLine;
use App\Models\SalesOrder;
use App\Models\WorkOrder;
use App\Services\Automation\AutomationLogger;
use Illuminate\Support\Facades\DB;

class MrpService
{
    public function __construct(private readonly ProductionService $production) {}

    /** @return array{work_orders: array<int, WorkOrder>, requisition: ?PurchaseRequisition} */
    public function plan(SalesOrder $order): array
    {
        return DB::transaction(function () use ($order) {
            $order->load('lines.product');
            $workOrders = [];
            $requirements = [];

            foreach ($order->lines as $line) {
                $product = $line->product;
                $onHand = (float) ProductStock::query()->where('product_id', $product->id)->sum('qty');
                $makeQty = $product->strategy === 'mts'
                    ? max(0, (float) $line->qty - $onHand)
                    : (float) $line->qty;

                if ($makeQty <= 0) {
                    continue;
                }

                $workOrder = $this->production->open($product, $makeQty, $product->strategy, $line);
                $this->production->release($workOrder);
                $workOrders[] = $workOrder;
                $this->collectMaterials($workOrder, $requirements);
            }

            $requisition = $this->requisitionFor($requirements, $order);
            $order->update(['status' => 'in_production']);

            AutomationLogger::record('sales_order', 'mrp', $order, 'planned', [
                'work_orders' => collect($workOrders)->pluck('number'),
                'requisition' => $requisition?->number,
            ]);

            return ['work_orders' => $workOrders, 'requisition' => $requisition];
        });
    }

    /** @param array<int, float> $requirements */
    private function requisitionFor(array $requirements, SalesOrder $order): ?PurchaseRequisition
    {
        $short = [];
        foreach ($requirements as $materialId => $qty) {
            $material = Material::query()->find($materialId);
            if (! $material) {
                continue;
            }
            $gap = $qty - $material->onHand();
            if ($gap > 0) {
                $short[$materialId] = $gap;
            }
        }

        if ($short === []) {
            return null;
        }

        $requisition = PurchaseRequisition::query()->create([
            'status' => 'open',
            'source' => 'mrp',
            'requested_by' => auth()->id(),
            'needed_by' => $order->promised_on,
            'notes' => "MRP for {$order->number}",
        ]);

        foreach ($short as $materialId => $qty) {
            $material = Material::query()->find($materialId);
            PurchaseRequisitionLine::query()->create([
                'purchase_requisition_id' => $requisition->id,
                'material_id' => $materialId,
                'qty' => round($qty, 3),
                'estimated_cost' => round($qty * (float) ($material->standard_cost ?? 0), 4),
            ]);
        }

        return $requisition;
    }

    /** @param array<int, float> $requirements */
    private function collectMaterials(WorkOrder $workOrder, array &$requirements): void
    {
        $workOrder->load('bom.items');
        $thousands = (float) $workOrder->qty_planned / 1000;

        foreach ($workOrder->bom?->items ?? [] as $item) {
            if ($item->component_type !== 'material' || ! $item->material_id) {
                continue;
            }
            $qty = (float) $item->quantity * (1 + (float) $item->scrap_factor) * $thousands;
            $requirements[$item->material_id] = ($requirements[$item->material_id] ?? 0) + $qty;
        }
    }
}
