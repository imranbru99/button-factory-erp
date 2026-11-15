<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'hr';
    }
}
