<?php

namespace App\Filament\Resources\LotResource\Pages;

use App\Filament\Resources\LotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLots extends ManageRecords
{
    protected static string $resource = LotResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
