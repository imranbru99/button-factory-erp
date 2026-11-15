<?php

namespace App\Policies;

use App\Models\MachineRuntime;
use App\Models\User;

class MachineRuntimePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'maintenance';
    }
}
