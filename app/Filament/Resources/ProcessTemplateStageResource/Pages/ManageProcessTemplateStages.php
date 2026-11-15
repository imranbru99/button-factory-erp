<?php

namespace App\Filament\Resources\ProcessTemplateStageResource\Pages;

use App\Filament\Resources\ProcessTemplateStageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProcessTemplateStages extends ManageRecords
{
    protected static string $resource = ProcessTemplateStageResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
