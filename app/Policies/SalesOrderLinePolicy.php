<?php

namespace App\Policies;

use App\Models\SalesOrderLine;
use App\Models\User;

class SalesOrderLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
