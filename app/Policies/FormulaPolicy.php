<?php

namespace App\Policies;

use App\Models\Formula;
use App\Models\User;

class FormulaPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'catalog';
    }
}
