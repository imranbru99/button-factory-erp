<?php

namespace App\Filament\Resources\JobCostResource\Pages;

use App\Filament\Resources\JobCostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJobCosts extends ManageRecords
{
    protected static string $resource = JobCostResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
