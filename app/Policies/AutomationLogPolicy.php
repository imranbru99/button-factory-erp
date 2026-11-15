<?php

namespace App\Policies;

use App\Models\AutomationLog;
use App\Models\User;

class AutomationLogPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'system';
    }
}
