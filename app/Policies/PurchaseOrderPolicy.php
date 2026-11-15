<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
