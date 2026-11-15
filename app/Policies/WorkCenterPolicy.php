<?php

namespace App\Policies;

use App\Models\WorkCenter;
use App\Models\User;

class WorkCenterPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'planning';
    }
}
