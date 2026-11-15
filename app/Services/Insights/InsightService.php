<?php

namespace App\Services\Insights;

use App\Models\Machine;
use App\Models\SalesOrder;
use App\Models\StageIssue;
use App\Models\WorkOrder;
use App\Models\WorkOrderStage;
use App\Services\Maintenance\OeeService;
use Tarikh\Tarikh;

class InsightService
{
    /** @return array<string, mixed> */
    public function snapshot(): array
    {
        $completed = WorkOrder::query()->whereDate('completed_at', today());
        $output = (float) (clone $completed)->sum('qty_completed');
        $scrap = (float) WorkOrderStage::query()->whereDate('ended_at', today())->sum('qty_scrap');
        $in = (float) WorkOrderStage::query()->whereDate('ended_at', today())->sum('qty_in');
        $due = SalesOrder::query()->whereDate('promised_on', '<=', today())->whereNotIn('status', ['shipped', 'closed', 'cancelled']);
        $onTime = SalesOrder::query()->whereIn('status', ['shipped', 'closed'])->whereColumn('updated_at', '<=', 'promised_on')->count();
        $shipped = SalesOrder::query()->whereIn('status', ['shipped', 'closed'])->count();

        $oee = Machine::query()->get()->map(fn (Machine $machine) => app(OeeService::class)->forMachine($machine));
        $avgOee = $oee->isEmpty() ? 0 : round($oee->avg('oee') * 100, 1);

        return [
            'clock' => Tarikh::now(config('factory.timezone'))->format('d M Y H:i'),
            'output_today' => $output,
            'scrap_rate' => $in > 0 ? round(($scrap / $in) * 100, 1) : 0,
            'open_orders' => WorkOrder::query()->whereIn('status', ['planned', 'released', 'in_progress', 'qc_hold'])->count(),
            'open_issues' => StageIssue::query()->where('status', 'open')->count(),
            'on_time' => $shipped > 0 ? round(($onTime / $shipped) * 100, 1) : 100,
            'overdue_sales' => $due->count(),
            'oee' => $avgOee,
        ];
    }
}
