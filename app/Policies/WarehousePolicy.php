<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\Models\User;

class WarehousePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'inventory';
    }
}
