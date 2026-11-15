<?php

namespace App\Policies;

use App\Models\QuotationLine;
use App\Models\User;

class QuotationLinePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
