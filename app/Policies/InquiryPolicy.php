<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\User;

class InquiryPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'sales';
    }
}
