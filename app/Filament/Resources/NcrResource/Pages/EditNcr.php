<?php

namespace App\Filament\Resources\NcrResource\Pages;

use App\Filament\Resources\NcrResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditNcr extends EditRecord
{
    protected static string $resource = NcrResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
