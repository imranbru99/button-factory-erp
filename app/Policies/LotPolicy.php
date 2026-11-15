<?php

namespace App\Policies;

use App\Models\Lot;
use App\Models\User;

class LotPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'inventory';
    }
}
