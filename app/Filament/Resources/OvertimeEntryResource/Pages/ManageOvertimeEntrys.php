<?php

namespace App\Filament\Resources\OvertimeEntryResource\Pages;

use App\Filament\Resources\OvertimeEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageOvertimeEntrys extends ManageRecords
{
    protected static string $resource = OvertimeEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
