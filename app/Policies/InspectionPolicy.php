<?php

namespace App\Policies;

use App\Models\Inspection;
use App\Models\User;

class InspectionPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'quality';
    }
}
