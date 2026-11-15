<?php

namespace App\Filament\Resources\EmployeeSkillResource\Pages;

use App\Filament\Resources\EmployeeSkillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEmployeeSkills extends ManageRecords
{
    protected static string $resource = EmployeeSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
