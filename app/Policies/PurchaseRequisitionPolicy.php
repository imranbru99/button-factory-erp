<?php

namespace App\Policies;

use App\Models\PurchaseRequisition;
use App\Models\User;

class PurchaseRequisitionPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
