<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
