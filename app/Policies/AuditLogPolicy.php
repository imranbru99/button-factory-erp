<?php

namespace App\Policies;

use App\Models\AuditLog;
use App\Models\User;

class AuditLogPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'system';
    }
}
