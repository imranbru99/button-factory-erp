<?php

namespace App\Services\Inventory;

use App\Models\Lot;
use App\Models\Material;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockService
{
    public function receive(
        Material $material,
        Warehouse $warehouse,
        string $lotNumber,
        float $qty,
        float $unitCost,
        ?Model $reference = null,
        ?Supplier $supplier = null,
    ): Lot {
        return DB::transaction(function () use ($material, $warehouse, $lotNumber, $qty, $unitCost, $reference, $supplier) {
            $lot = Lot::query()->firstOrNew([
                'material_id' => $material->id,
                'warehouse_id' => $warehouse->id,
                'lot_number' => $lotNumber,
            ]);

            $existing = (float) $lot->qty_on_hand;
            $combined = $existing + $qty;
            $lot->unit_cost = $combined > 0
                ? (($existing * (float) ($lot->unit_cost ?: $unitCost)) + ($qty * $unitCost)) / $combined
                : $unitCost;
            $lot->qty_on_hand = $combined;
            $lot->received_at = $lot->received_at ?: now();
            $lot->status = 'available';
            $lot->supplier_id = $supplier?->id ?? $lot->supplier_id;
            $lot->save();

            $this->move($material, $lot, $warehouse, 'receipt', $qty, $unitCost, $reference, 'Goods receipt');
            app(ReorderService::class)->check($material);

            return $lot->refresh();
        });
    }

    /**
     * Issue stock. FIFO consumes the oldest lots at their own cost.
     * Weighted average consumes the oldest lots physically and values the issue at the current average.
     */
    public function issue(Material $material, float $qty, ?Warehouse $warehouse, ?Model $reference = null, string $type = 'issue'): float
    {
        if ($qty <= 0) {
            return 0.0;
        }

        return DB::transaction(function () use ($material, $qty, $warehouse, $reference, $type) {
            $lots = Lot::query()
                ->where('material_id', $material->id)
                ->where('qty_on_hand', '>', 0)
                ->when($warehouse, fn ($query) => $query->where('warehouse_id', $warehouse->id))
                ->orderBy('received_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $available = (float) $lots->sum('qty_on_hand');
            if ($available + 0.0001 < $qty) {
                throw new RuntimeException("Insufficient {$material->sku} stock. Need {$qty}, have {$available}.");
            }

            $unit = (float) $lots->first()->unit_cost;
            if ($material->valuation_method === 'weighted_average') {
                $value = $lots->sum(fn (Lot $lot) => (float) $lot->qty_on_hand * (float) $lot->unit_cost);
                $unit = $available > 0 ? $value / $available : (float) $material->standard_cost;
            }

            $remaining = $qty;
            $cost = 0.0;

            foreach ($lots as $lot) {
                if ($remaining <= 0) {
                    break;
                }

                $take = min($remaining, (float) $lot->qty_on_hand);
                $issueCost = $material->valuation_method === 'weighted_average' ? $unit : (float) $lot->unit_cost;
                $lot->qty_on_hand = round((float) $lot->qty_on_hand - $take, 3);
                if ($lot->qty_on_hand <= 0) {
                    $lot->qty_on_hand = 0;
                    $lot->status = 'consumed';
                }
                if ($material->valuation_method === 'weighted_average') {
                    $lot->unit_cost = $unit;
                }
                $lot->save();

                $this->move($material, $lot, $lot->warehouse, $type, -$take, $issueCost, $reference, 'Stock issue');
                $cost += $take * $issueCost;
                $remaining -= $take;
            }

            app(ReorderService::class)->check($material->fresh());

            return round($cost, 4);
        });
    }

    public function onHand(Material $material): float
    {
        return (float) $material->lots()->sum('qty_on_hand');
    }

    private function move(Material $material, Lot $lot, Warehouse $warehouse, string $type, float $qty, float $unitCost, ?Model $reference, string $notes): void
    {
        StockMovement::query()->create([
            'material_id' => $material->id,
            'lot_id' => $lot->id,
            'warehouse_id' => $warehouse->id,
            'type' => $type,
            'qty' => $qty,
            'unit_cost' => $unitCost,
            'reference_type' => $reference?->getMorphClass(),
            'reference_id' => $reference?->getKey(),
            'notes' => $notes,
            'user_id' => auth()->id(),
        ]);
    }
}
