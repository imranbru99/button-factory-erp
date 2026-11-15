<?php

namespace App\Filament\Resources\InvoiceLineResource\Pages;

use App\Filament\Resources\InvoiceLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoiceLines extends ManageRecords
{
    protected static string $resource = InvoiceLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
