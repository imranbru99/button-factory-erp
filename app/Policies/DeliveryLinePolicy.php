<?php

namespace App\Policies;

use App\Models\DeliveryLine;
use App\Models\User;

class DeliveryLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
