<?php

namespace App\Filament\Resources\MaintenancePlanResource\Pages;

use App\Filament\Resources\MaintenancePlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMaintenancePlans extends ManageRecords
{
    protected static string $resource = MaintenancePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
