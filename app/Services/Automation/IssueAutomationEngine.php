<?php

namespace App\Services\Automation;

use App\Models\Machine;
use App\Models\MaintenanceTicket;
use App\Models\Material;
use App\Models\StageIssue;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderStage;
use App\Notifications\FactoryAlertNotification;
use App\Services\Ai\AiAdvisor;
use App\Services\Production\ProductionService;

class IssueAutomationEngine
{
    public function __construct(private readonly AiAdvisor $ai) {}

    public function report(WorkOrderStage $stage, string $defect, string $description, ?string $photo = null, string $severity = 'medium'): StageIssue
    {
        $issue = StageIssue::query()->create([
            'work_order_stage_id' => $stage->id,
            'work_order_id' => $stage->work_order_id,
            'defect_code' => $defect,
            'severity' => $severity,
            'description' => $description,
            'photo_path' => $photo,
            'status' => 'open',
            'reported_by' => auth()->id(),
        ]);

        $analysis = $this->ai->analyzeIssue($issue);
        $issue->update([
            'ai_analysis' => $analysis,
            'suggested_actions' => $analysis['corrective_actions'] ?? [],
        ]);

        $this->act($issue->refresh());

        return $issue->refresh();
    }

    public function afterStage(WorkOrderStage $stage): void
    {
        $input = (float) $stage->qty_in;
        if ($input <= 0) {
            return;
        }

        $ratio = (float) $stage->qty_scrap / $input;
        if ($ratio < (float) config('factory.scrap_alert_ratio', 0.08)) {
            return;
        }

        $defect = $stage->code === 'drilling' ? 'off_center_holes' : 'thickness_variation';
        $this->report(
            $stage,
            $defect,
            sprintf('Scrap %.1f%% at %s exceeded the %.0f%% limit.', $ratio * 100, $stage->name, config('factory.scrap_alert_ratio') * 100),
            null,
            'high',
        );
    }

    public function machineDown(Machine $machine, string $reason): MaintenanceTicket
    {
        $machine->update(['status' => 'breakdown']);

        $ticket = MaintenanceTicket::query()->create([
            'machine_id' => $machine->id,
            'priority' => 'urgent',
            'status' => 'open',
            'title' => "Breakdown: {$machine->name}",
            'description' => $reason,
            'suggested_solution' => $this->ai->maintenanceHint($machine, $reason),
            'source' => 'automation',
            'opened_by' => auth()->id(),
        ]);

        $alternate = Machine::query()
            ->where('machine_type', $machine->machine_type)
            ->where('id', '!=', $machine->id)
            ->whereIn('status', ['idle', 'running'])
            ->first();

        $moved = 0;
        if ($alternate) {
            $moved = WorkOrderStage::query()
                ->where('machine_id', $machine->id)
                ->whereIn('status', ['pending', 'paused'])
                ->update(['machine_id' => $alternate->id]);
        }

        AutomationLogger::record('machine', 'breakdown_reallocation', $machine, 'maintenance_ticket', [
            'ticket' => $ticket->number,
            'alternate' => $alternate?->code,
            'stages_moved' => $moved,
        ]);

        $this->alert(['super_admin', 'production_manager'], 'Machine breakdown', $ticket->title, url('/admin/maintenance-tickets'));

        return $ticket;
    }

    private function act(StageIssue $issue): void
    {
        $order = $issue->workOrder;
        $actions = $issue->suggested_actions ?? [];

        if ($issue->defect_code === 'color_mismatch') {
            $order->update(['status' => 'qc_hold']);
            $order->stages()->whereIn('status', ['pending', 'paused'])->update(['status' => 'blocked']);
            $actions[] = 'Related batches held. Review the active polyester formula before release.';
            AutomationLogger::record('quality', 'color_hold', $order, 'qc_hold', ['issue' => $issue->id]);
        }

        if (in_array($issue->defect_code, ['off_center_holes', 'cracked_edges'], true) || $issue->stage?->code === 'drilling') {
            $machine = $issue->stage?->machine ?? Machine::query()->where('machine_type', 'drilling')->first();
            if ($machine) {
                $ticket = MaintenanceTicket::query()->create([
                    'machine_id' => $machine->id,
                    'priority' => 'high',
                    'status' => 'open',
                    'title' => 'Calibrate '.$machine->name,
                    'description' => $issue->description,
                    'suggested_solution' => 'Check drill jig concentricity, spindle play, and blank seating. Compare the last good SPC sample.',
                    'source' => 'automation',
                    'opened_by' => auth()->id(),
                ]);
                $actions[] = "Maintenance ticket {$ticket->number} opened for calibration.";
                AutomationLogger::record('quality', 'drill_calibration', $issue, 'maintenance_ticket', ['ticket' => $ticket->number]);
            }
        }

        if ((float) $issue->stage?->qty_scrap > 0 && $order->status !== 'qc_hold') {
            $scrap = (float) $issue->stage->qty_scrap;
            if ($scrap >= 50) {
                app(ProductionService::class)->open($order->product, $scrap, $order->strategy, $order->salesOrderLine, true, $order);
                $actions[] = 'Rework work order created for the scrapped quantity.';
                AutomationLogger::record('quality', 'rework_order', $order, 'rework', ['qty' => $scrap]);
            }
        }

        if ($issue->defect_code === 'bubbles') {
            $resin = Material::query()->where('category', 'resin')->first();
            if ($resin && $resin->needsReorder()) {
                $actions[] = 'Resin is below minimum. A purchase request is already required.';
            }
            $actions[] = 'Check catalyst ratio, mixing time, and casting temperature on the active formula.';
        }

        $issue->update(['suggested_actions' => array_values(array_unique($actions))]);

        $this->alert(
            ['super_admin', 'production_manager', 'qc'],
            'Shop-floor issue',
            $issue->defect_code.': '.$issue->description,
            url('/admin/stage-issues'),
        );
    }

    /** @param array<int, string> $roles */
    private function alert(array $roles, string $title, string $body, string $url): void
    {
        User::query()->whereIn('role', $roles)->where('is_active', true)->get()
            ->each(fn (User $user) => $user->notify(new FactoryAlertNotification($title, $body, $url)));
    }
}
