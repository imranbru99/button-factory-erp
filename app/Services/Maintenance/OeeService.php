<?php

namespace App\Services\Maintenance;

use App\Models\Machine;
use App\Models\MachineRuntime;

class OeeService
{
    /** @return array{availability: float, performance: float, quality: float, oee: float} */
    public function forMachine(Machine $machine): array
    {
        $runs = MachineRuntime::query()->where('machine_id', $machine->id)->whereNotNull('ended_at')->get();
        if ($runs->isEmpty()) {
            return ['availability' => 0, 'performance' => 0, 'quality' => 0, 'oee' => 0];
        }

        $planned = (float) $runs->sum('planned_minutes');
        $downtime = (float) $runs->sum('downtime_minutes');
        $availability = $planned > 0 ? max(0, ($planned - $downtime) / $planned) : 1;

        $good = (float) $runs->sum('good_qty');
        $total = (float) $runs->sum('total_qty');
        $quality = $total > 0 ? $good / $total : 1;

        $actualMinutes = $runs->sum(function (MachineRuntime $run) {
            return $run->started_at && $run->ended_at ? $run->started_at->diffInMinutes($run->ended_at) : 0;
        });
        $capacity = (float) $machine->capacity_per_hour;
        $expected = $capacity > 0 && $actualMinutes > 0 ? ($capacity * ($actualMinutes / 60)) : $total;
        $performance = $expected > 0 ? min(1, $total / $expected) : 1;

        $oee = $availability * $performance * $quality;

        return [
            'availability' => round($availability, 4),
            'performance' => round($performance, 4),
            'quality' => round($quality, 4),
            'oee' => round($oee, 4),
        ];
    }
}
