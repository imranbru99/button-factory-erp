<?php

namespace App\Policies;

use App\Models\Bin;
use App\Models\User;

class BinPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'inventory';
    }
}
