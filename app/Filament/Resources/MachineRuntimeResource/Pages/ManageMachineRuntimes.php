<?php

namespace App\Filament\Resources\MachineRuntimeResource\Pages;

use App\Filament\Resources\MachineRuntimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMachineRuntimes extends ManageRecords
{
    protected static string $resource = MachineRuntimeResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
