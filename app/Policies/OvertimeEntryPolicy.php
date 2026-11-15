<?php

namespace App\Policies;

use App\Models\OvertimeEntry;
use App\Models\User;

class OvertimeEntryPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'hr';
    }
}
