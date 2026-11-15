<?php

namespace App\Policies;

use App\Models\MaintenancePlan;
use App\Models\User;

class MaintenancePlanPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'maintenance';
    }
}
