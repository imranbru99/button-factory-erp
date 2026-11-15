<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'hr';
    }
}
