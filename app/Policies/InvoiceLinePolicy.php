<?php

namespace App\Policies;

use App\Models\InvoiceLine;
use App\Models\User;

class InvoiceLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'finance';
    }
}
