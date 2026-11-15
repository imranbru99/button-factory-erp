<?php

namespace App\Policies;

use App\Models\WorkOrder;
use App\Models\User;

class WorkOrderPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'shopfloor';
    }
}
