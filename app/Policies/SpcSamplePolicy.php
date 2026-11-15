<?php

namespace App\Policies;

use App\Models\SpcSample;
use App\Models\User;

class SpcSamplePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'quality';
    }
}
