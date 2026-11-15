<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;

class DeliveryPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
