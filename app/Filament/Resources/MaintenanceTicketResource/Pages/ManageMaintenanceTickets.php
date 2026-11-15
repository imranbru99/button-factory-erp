<?php

namespace App\Filament\Resources\MaintenanceTicketResource\Pages;

use App\Filament\Resources\MaintenanceTicketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMaintenanceTickets extends ManageRecords
{
    protected static string $resource = MaintenanceTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
