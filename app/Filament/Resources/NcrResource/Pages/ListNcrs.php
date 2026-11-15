<?php

namespace App\Filament\Resources\NcrResource\Pages;

use App\Filament\Resources\NcrResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListNcrs extends ListRecords
{
    protected static string $resource = NcrResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
