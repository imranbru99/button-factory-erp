<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'catalog';
    }
}
