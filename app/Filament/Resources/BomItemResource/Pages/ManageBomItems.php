<?php

namespace App\Filament\Resources\BomItemResource\Pages;

use App\Filament\Resources\BomItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBomItems extends ManageRecords
{
    protected static string $resource = BomItemResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
