<?php

namespace App\Policies;

use App\Models\PurchaseRequisitionLine;
use App\Models\User;

class PurchaseRequisitionLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
