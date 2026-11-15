<?php

namespace App\Filament\Resources\PurchaseRequisitionLineResource\Pages;

use App\Filament\Resources\PurchaseRequisitionLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePurchaseRequisitionLines extends ManageRecords
{
    protected static string $resource = PurchaseRequisitionLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
