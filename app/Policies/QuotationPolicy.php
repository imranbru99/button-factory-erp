<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
