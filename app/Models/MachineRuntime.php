<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MachineRuntime extends Model
{
    use HasFactory;

    protected $table = 'machine_runtimes';

    protected $fillable = [
        'machine_id',
        'work_order_id',
        'started_at',
        'ended_at',
        'planned_minutes',
        'downtime_minutes',
        'good_qty',
        'total_qty',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'planned_minutes' => 'decimal:2',
            'downtime_minutes' => 'decimal:2',
            'good_qty' => 'decimal:3',
            'total_qty' => 'decimal:3',
        ];
    }



    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
}
