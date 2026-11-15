<?php

namespace App\Filament\Resources\StageIssueResource\Pages;

use App\Filament\Resources\StageIssueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStageIssues extends ManageRecords
{
    protected static string $resource = StageIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
