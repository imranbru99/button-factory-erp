<?php

namespace App\Policies;

use App\Models\Ncr;
use App\Models\User;

class NcrPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'quality';
    }
}
