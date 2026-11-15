<?php

namespace App\Policies;

use App\Models\Capa;
use App\Models\User;

class CapaPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'quality';
    }
}
