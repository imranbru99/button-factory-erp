<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use App\Models\Machine;
use App\Models\WorkOrder;
use App\Models\WorkOrderStage;
use App\Services\Automation\IssueAutomationEngine;
use App\Services\Production\ProductionService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;
use Livewire\WithFileUploads;

class ShopFloor extends Page
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Shop floor';

    protected string $view = 'filament.pages.shop-floor';

    public ?int $workOrderId = null;

    public ?int $operatorId = null;

    public ?int $machineId = null;

    public $qtyOut = 0;

    public $qtyScrap = 0;

    public string $defectCode = 'off_center_holes';

    public string $issueDescription = '';

    public $photo;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) config('factory.modules.shopfloor')
            && $user
            && $user->canModule('shopfloor', 'view');
    }

    public function mount(): void
    {
        $this->workOrderId = WorkOrder::query()
            ->whereIn('status', ['released', 'in_progress', 'qc_hold'])
            ->latest('id')
            ->value('id');
        $this->operatorId = auth()->user()?->employee?->id;
    }

    public function startStage(int $stageId): void
    {
        $this->guardManage();
        try {
            app(ProductionService::class)->start(
                WorkOrderStage::query()->findOrFail($stageId),
                Employee::query()->find($this->operatorId),
                Machine::query()->find($this->machineId),
            );
            Notification::make()->title('Stage started')->success()->send();
        } catch (ValidationException $exception) {
            Notification::make()->title(collect($exception->errors())->flatten()->first())->danger()->send();
        }
    }

    public function completeStage(int $stageId): void
    {
        $this->guardManage();
        $this->validate([
            'qtyOut' => ['required', 'numeric', 'min:0'],
            'qtyScrap' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            app(ProductionService::class)->complete(
                WorkOrderStage::query()->findOrFail($stageId),
                (float) $this->qtyOut,
                (float) $this->qtyScrap,
            );
            Notification::make()->title('Stage completed')->success()->send();
        } catch (ValidationException $exception) {
            Notification::make()->title(collect($exception->errors())->flatten()->first())->danger()->send();
        } catch (\Throwable $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();
        }
    }

    public function reportIssue(int $stageId): void
    {
        $this->guardManage();
        $this->validate([
            'defectCode' => ['required', 'string'],
            'issueDescription' => ['required', 'string', 'min:5'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $path = $this->photo?->store('issues', 'public');
        app(IssueAutomationEngine::class)->report(
            WorkOrderStage::query()->findOrFail($stageId),
            $this->defectCode,
            $this->issueDescription,
            $path,
        );
        $this->reset('issueDescription', 'photo');
        Notification::make()->title('Issue logged and corrective actions queued')->success()->send();
    }

    private function guardManage(): void
    {
        abort_unless(auth()->user()?->canModule('shopfloor', 'manage'), 403);
    }
}
