<?php

namespace App\Services\Automation;

use App\Models\AutomationLog;
use Illuminate\Database\Eloquent\Model;

class AutomationLogger
{
    public static function record(string $trigger, string $rule, ?Model $subject, string $action, array $payload = []): AutomationLog
    {
        return AutomationLog::query()->create([
            'trigger' => $trigger,
            'rule' => $rule,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'action' => $action,
            'payload' => $payload,
        ]);
    }
}
