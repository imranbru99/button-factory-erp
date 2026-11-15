<?php

namespace App\Policies;

use App\Models\PurchaseOrderLine;
use App\Models\User;

class PurchaseOrderLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
