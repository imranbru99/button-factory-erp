<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;

class PaymentPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'finance';
    }
}
