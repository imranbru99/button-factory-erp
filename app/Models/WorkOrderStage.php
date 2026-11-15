<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrderStage extends Model
{
    use HasFactory;

    protected $table = 'work_order_stages';

    protected $fillable = [
        'work_order_id',
        'code',
        'name',
        'sequence',
        'status',
        'operator_id',
        'machine_id',
        'started_at',
        'ended_at',
        'expected_minutes',
        'qty_in',
        'qty_out',
        'qty_scrap',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'expected_minutes' => 'decimal:2',
            'qty_in' => 'decimal:3',
            'qty_out' => 'decimal:3',
            'qty_scrap' => 'decimal:3',
        ];
    }



    public function issues(): HasMany
    {
        return $this->hasMany(StageIssue::class, 'work_order_stage_id');
    }
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
    public function operator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'operator_id');
    }
    public function machine(): BelongsTo
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }
}
