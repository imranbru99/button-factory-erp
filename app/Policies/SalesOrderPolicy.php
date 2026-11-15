<?php

namespace App\Policies;

use App\Models\SalesOrder;
use App\Models\User;

class SalesOrderPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
