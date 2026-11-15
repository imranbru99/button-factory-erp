<?php

namespace App\Policies;

use App\Models\JobCost;
use App\Models\User;

class JobCostPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'finance';
    }
}
