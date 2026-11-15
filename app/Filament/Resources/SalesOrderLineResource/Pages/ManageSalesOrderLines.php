<?php

namespace App\Filament\Resources\SalesOrderLineResource\Pages;

use App\Filament\Resources\SalesOrderLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSalesOrderLines extends ManageRecords
{
    protected static string $resource = SalesOrderLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
