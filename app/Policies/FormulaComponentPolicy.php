<?php

namespace App\Policies;

use App\Models\FormulaComponent;
use App\Models\User;

class FormulaComponentPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'catalog';
    }
}
