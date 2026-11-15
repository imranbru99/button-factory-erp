<?php

namespace App\Filament\Resources\QuotationLineResource\Pages;

use App\Filament\Resources\QuotationLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageQuotationLines extends ManageRecords
{
    protected static string $resource = QuotationLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
