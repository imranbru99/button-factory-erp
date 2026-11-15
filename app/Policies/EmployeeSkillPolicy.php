<?php

namespace App\Policies;

use App\Models\EmployeeSkill;
use App\Models\User;

class EmployeeSkillPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'hr';
    }
}
