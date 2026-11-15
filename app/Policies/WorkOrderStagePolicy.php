<?php

namespace App\Policies;

use App\Models\WorkOrderStage;
use App\Models\User;

class WorkOrderStagePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'shopfloor';
    }
}
