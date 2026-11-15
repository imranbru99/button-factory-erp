<?php

namespace App\Policies;

use App\Models\GoodsReceipt;
use App\Models\User;

class GoodsReceiptPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
