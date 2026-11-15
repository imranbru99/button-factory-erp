<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'catalog';
    }
}
