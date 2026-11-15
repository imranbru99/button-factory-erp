<?php

namespace App\Policies;

use App\Models\BomItem;
use App\Models\User;

class BomItemPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'catalog';
    }
}
