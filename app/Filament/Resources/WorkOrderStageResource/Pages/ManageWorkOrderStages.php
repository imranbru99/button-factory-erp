<?php

namespace App\Filament\Resources\WorkOrderStageResource\Pages;

use App\Filament\Resources\WorkOrderStageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWorkOrderStages extends ManageRecords
{
    protected static string $resource = WorkOrderStageResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
