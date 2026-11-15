<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'finance';
    }
}
