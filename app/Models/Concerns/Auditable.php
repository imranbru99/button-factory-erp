<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function (Model $model) use ($event) {
                AuditLog::query()->create([
                    'user_id' => auth()->id(),
                    'auditable_type' => $model->getMorphClass(),
                    'auditable_id' => $model->getKey(),
                    'event' => $event,
                    'old_values' => $event === 'created' ? null : $model->getOriginal(),
                    'new_values' => $event === 'deleted' ? null : $model->getAttributes(),
                    'ip' => request()?->ip(),
                ]);
            });
        }
    }
}
