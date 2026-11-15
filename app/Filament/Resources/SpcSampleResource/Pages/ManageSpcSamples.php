<?php

namespace App\Filament\Resources\SpcSampleResource\Pages;

use App\Filament\Resources\SpcSampleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSpcSamples extends ManageRecords
{
    protected static string $resource = SpcSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
