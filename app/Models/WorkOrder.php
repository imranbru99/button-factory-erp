<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasDocumentNumber;

class WorkOrder extends Model
{
    use HasFactory, Auditable, HasDocumentNumber;

    protected $table = 'work_orders';

    protected $fillable = [
        'number',
        'product_id',
        'sales_order_line_id',
        'bom_id',
        'formula_id',
        'parent_work_order_id',
        'is_rework',
        'qty_planned',
        'qty_completed',
        'qty_scrapped',
        'status',
        'strategy',
        'priority',
        'scheduled_start',
        'scheduled_end',
        'released_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_rework' => 'boolean',
            'qty_planned' => 'decimal:3',
            'qty_completed' => 'decimal:3',
            'qty_scrapped' => 'decimal:3',
            'priority' => 'integer',
            'scheduled_start' => 'datetime',
            'scheduled_end' => 'datetime',
            'released_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function documentPrefix(): string
    {
        return 'WO';
    }


    public function scrapRate(): float
    {
        $planned = (float) $this->qty_planned;

        return $planned > 0 ? (float) $this->qty_scrapped / $planned : 0.0;
    }


    public function stages(): HasMany
    {
        return $this->hasMany(WorkOrderStage::class, 'work_order_id');
    }
    public function issues(): HasMany
    {
        return $this->hasMany(StageIssue::class, 'work_order_id');
    }
    public function jobCost(): HasOne
    {
        return $this->hasOne(JobCost::class, 'work_order_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'parent_work_order_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function salesOrderLine(): BelongsTo
    {
        return $this->belongsTo(SalesOrderLine::class, 'sales_order_line_id');
    }
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class, 'bom_id');
    }
    public function formula(): BelongsTo
    {
        return $this->belongsTo(Formula::class, 'formula_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'parent_work_order_id');
    }
}
