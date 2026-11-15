<?php

namespace App\Filament\Resources\FormulaResource\Pages;

use App\Filament\Resources\FormulaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFormula extends EditRecord
{
    protected static string $resource = FormulaResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
