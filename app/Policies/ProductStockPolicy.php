<?php

namespace App\Policies;

use App\Models\ProductStock;
use App\Models\User;

class ProductStockPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'inventory';
    }
}
