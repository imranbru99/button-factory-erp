<?php

namespace App\Filament\Resources\WorkCenterResource\Pages;

use App\Filament\Resources\WorkCenterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWorkCenters extends ManageRecords
{
    protected static string $resource = WorkCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
