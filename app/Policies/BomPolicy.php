<?php

namespace App\Policies;

use App\Models\Bom;
use App\Models\User;

class BomPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'catalog';
    }
}
