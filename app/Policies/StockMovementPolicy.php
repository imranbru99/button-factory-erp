<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;

class StockMovementPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'inventory';
    }
}
