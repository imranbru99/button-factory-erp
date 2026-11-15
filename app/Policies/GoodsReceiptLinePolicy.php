<?php

namespace App\Policies;

use App\Models\GoodsReceiptLine;
use App\Models\User;

class GoodsReceiptLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'procurement';
    }
}
