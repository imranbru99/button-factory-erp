<?php

namespace App\Filament\Resources\PurchaseOrderLineResource\Pages;

use App\Filament\Resources\PurchaseOrderLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePurchaseOrderLines extends ManageRecords
{
    protected static string $resource = PurchaseOrderLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
