<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
