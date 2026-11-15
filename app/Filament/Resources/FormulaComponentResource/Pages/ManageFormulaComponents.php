<?php

namespace App\Filament\Resources\FormulaComponentResource\Pages;

use App\Filament\Resources\FormulaComponentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFormulaComponents extends ManageRecords
{
    protected static string $resource = FormulaComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
