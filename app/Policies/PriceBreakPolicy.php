<?php

namespace App\Policies;

use App\Models\PriceBreak;
use App\Models\User;

class PriceBreakPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
