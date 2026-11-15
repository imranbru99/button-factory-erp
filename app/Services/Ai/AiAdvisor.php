<?php

namespace App\Services\Ai;

use App\Models\Machine;
use App\Models\StageIssue;
use ImranDevBd\AiHub\Facades\AIHub;
use Throwable;

class AiAdvisor
{
    /** @return array<string, mixed> */
    public function analyzeIssue(StageIssue $issue): array
    {
        $fallback = $this->heuristic($issue->defect_code, $issue->description);

        if (! config('factory.ai.enabled')) {
            return $fallback;
        }

        try {
            $response = AIHub::provider()
                ->system('You are a senior button-factory process engineer. Return only the requested JSON.')
                ->prompt("Defect {$issue->defect_code} on stage {$issue->stage?->name}. {$issue->description}")
                ->structured([
                    'type' => 'object',
                    'properties' => [
                        'likely_cause' => ['type' => 'string'],
                        'corrective_actions' => ['type' => 'array', 'items' => ['type' => 'string']],
                        'hold_batch' => ['type' => 'boolean'],
                    ],
                    'required' => ['likely_cause', 'corrective_actions', 'hold_batch'],
                ])
                ->generate();

            $json = $response->json();
            if (is_array($json) && isset($json['likely_cause'])) {
                $json['source'] = 'ai-hub';

                return $json;
            }
        } catch (Throwable $exception) {
            $fallback['ai_error'] = $exception->getMessage();
        }

        return $fallback;
    }

    public function maintenanceHint(Machine $machine, string $reason): string
    {
        $base = "Inspect {$machine->name} ({$machine->machine_type}). Last reported: {$reason}.";

        if (! config('factory.ai.enabled')) {
            return $base.' Check alignment, lubrication, tooling wear, and the previous ticket on this machine.';
        }

        try {
            $response = AIHub::provider()
                ->system('Reply with one short maintenance instruction for a button factory.')
                ->prompt($base)
                ->generate();

            return trim($response->content) !== '' ? trim($response->content) : $base;
        } catch (Throwable) {
            return $base;
        }
    }

    /** @return array<string, mixed> */
    private function heuristic(string $code, string $description): array
    {
        $map = [
            'off_center_holes' => ['Drill jig is off center or the blank is not seated.', ['Calibrate the drilling machine', 'Check the jig and collet', 'Quarantine the current lot']],
            'cracked_edges' => ['Blank was too brittle or the tool was dull.', ['Reduce turning feed', 'Inspect cutter', 'Review cure time']],
            'thickness_variation' => ['Casting sheet thickness drifted.', ['Check casting drum gap', 'Hold the sheet lot', 'Record an SPC sample']],
            'color_mismatch' => ['Pigment percentage or mix time drifted from the formula.', ['Hold the batch', 'Review the active formula version', 'Compare the retained swatch']],
            'bubbles' => ['Air was beaten into the resin or the catalyst was high.', ['Slow the mixer', 'Confirm catalyst percentage', 'Check vacuum or rest time']],
            'poor_polish' => ['Media is worn or the barrel time was short.', ['Replace polishing media', 'Extend barrel time', 'Inspect the barrel']],
        ];

        [$cause, $actions] = $map[$code] ?? ['Process drift recorded on the shop floor.', ['Review the stage record', 'Notify the production manager']];

        return [
            'source' => 'heuristic',
            'likely_cause' => $cause,
            'corrective_actions' => $actions,
            'hold_batch' => $code === 'color_mismatch',
            'description' => $description,
        ];
    }
}
