<?php

namespace App\Filament\Resources\CapaResource\Pages;

use App\Filament\Resources\CapaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCapas extends ManageRecords
{
    protected static string $resource = CapaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
