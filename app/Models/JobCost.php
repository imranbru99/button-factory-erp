<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCost extends Model
{
    use HasFactory;

    protected $table = 'job_costs';

    protected $fillable = [
        'work_order_id',
        'material_cost',
        'labor_cost',
        'machine_cost',
        'overhead_cost',
        'total_cost',
        'unit_cost',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'material_cost' => 'decimal:2',
            'labor_cost' => 'decimal:2',
            'machine_cost' => 'decimal:2',
            'overhead_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'unit_cost' => 'decimal:4',
            'calculated_at' => 'datetime',
        ];
    }



    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
}
