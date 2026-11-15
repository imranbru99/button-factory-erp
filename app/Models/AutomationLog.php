<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AutomationLog extends Model
{
    use HasFactory;

    protected $table = 'automation_logs';

    protected $fillable = [
        'trigger',
        'rule',
        'subject_type',
        'subject_id',
        'action',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'subject_id' => 'integer',
            'payload' => 'array',
        ];
    }



    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
