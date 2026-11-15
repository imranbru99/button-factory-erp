<?php

namespace App\Filament\Resources\DefectResource\Pages;

use App\Filament\Resources\DefectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDefects extends ManageRecords
{
    protected static string $resource = DefectResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
