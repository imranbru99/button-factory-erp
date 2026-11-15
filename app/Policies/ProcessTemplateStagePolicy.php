<?php

namespace App\Policies;

use App\Models\ProcessTemplateStage;
use App\Models\User;

class ProcessTemplateStagePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'planning';
    }
}
