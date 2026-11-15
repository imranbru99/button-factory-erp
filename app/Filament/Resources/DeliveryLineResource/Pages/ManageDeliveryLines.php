<?php

namespace App\Filament\Resources\DeliveryLineResource\Pages;

use App\Filament\Resources\DeliveryLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDeliveryLines extends ManageRecords
{
    protected static string $resource = DeliveryLineResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
