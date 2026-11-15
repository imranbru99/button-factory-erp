<?php

namespace App\Policies;

use App\Models\ProcessTemplate;
use App\Models\User;

class ProcessTemplatePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'planning';
    }
}
