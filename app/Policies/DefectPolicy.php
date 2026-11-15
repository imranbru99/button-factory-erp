<?php

namespace App\Policies;

use App\Models\Defect;
use App\Models\User;

class DefectPolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'quality';
    }
}
