<?php

namespace App\Policies;

use App\Models\Shift;
use App\Models\User;

class ShiftPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'hr';
    }
}
