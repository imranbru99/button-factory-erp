<?php

namespace App\Filament\Resources\PriceBreakResource\Pages;

use App\Filament\Resources\PriceBreakResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePriceBreaks extends ManageRecords
{
    protected static string $resource = PriceBreakResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
