<?php

namespace App\Filament\Resources\ProcessTemplateResource\Pages;

use App\Filament\Resources\ProcessTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageProcessTemplates extends ManageRecords
{
    protected static string $resource = ProcessTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
