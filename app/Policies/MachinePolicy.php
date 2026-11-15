<?php

namespace App\Policies;

use App\Models\Machine;
use App\Models\User;

class MachinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'maintenance';
    }
}
