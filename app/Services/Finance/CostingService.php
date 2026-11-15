<?php

namespace App\Services\Finance;

use App\Models\JobCost;
use App\Models\StockMovement;
use App\Models\WorkOrder;

class CostingService
{
    public function calculate(WorkOrder $order): JobCost
    {
        $order->load('stages.operator', 'stages.machine');

        $material = (float) StockMovement::query()
            ->where('reference_type', $order->getMorphClass())
            ->where('reference_id', $order->id)
            ->where('type', 'production_consume')
            ->get()
            ->sum(fn (StockMovement $movement) => abs((float) $movement->qty) * (float) $movement->unit_cost);

        $labor = 0.0;
        $machine = 0.0;

        foreach ($order->stages as $stage) {
            $minutes = 0.0;
            if ($stage->started_at && $stage->ended_at) {
                $minutes = $stage->started_at->diffInMinutes($stage->ended_at);
            }
            $minutes = $minutes > 0 ? $minutes : (float) $stage->expected_minutes;
            $labor += ($minutes / 60) * (float) ($stage->operator->hourly_rate ?? 0);
            $machine += ($minutes / 60) * (float) ($stage->machine->hourly_rate ?? 0);
        }

        $overhead = ($labor + $machine) * (float) config('factory.overhead_rate', 0.15);
        $total = $material + $labor + $machine + $overhead;
        $completed = max((float) $order->qty_completed, 1);

        return JobCost::query()->updateOrCreate(
            ['work_order_id' => $order->id],
            [
                'material_cost' => round($material, 2),
                'labor_cost' => round($labor, 2),
                'machine_cost' => round($machine, 2),
                'overhead_cost' => round($overhead, 2),
                'total_cost' => round($total, 2),
                'unit_cost' => round($total / $completed, 4),
                'calculated_at' => now(),
            ],
        );
    }
}
