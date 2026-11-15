<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaintenancePlan extends Model
{
    use HasFactory;

    protected $table = 'maintenance_plans';

    protected $fillable = [
        'machine_id',
        'task',
        'interval_days',
        'last_done_on',
        'next_due_on',
    ];

    protected function casts(): array
    {
        return [
            'interval_days' => 'integer',
            'last_done_on' => 'date',
            'next_due_on' => 'date',
        ];
    }



    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
}
