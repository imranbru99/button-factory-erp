<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'hr';
    }
}
