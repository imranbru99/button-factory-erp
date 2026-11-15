<?php

namespace App\Policies;

use App\Models\Material;
use App\Models\User;

class MaterialPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'inventory';
    }
}
