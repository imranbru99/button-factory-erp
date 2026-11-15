<?php

namespace App\Policies;

use App\Models\StageIssue;
use App\Models\User;

class StageIssuePolicy extends ModulePolicy
{
    protected function module(): string
    {
        return 'quality';
    }
}
