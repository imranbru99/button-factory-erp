<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StageIssue extends Model
{
    use HasFactory;

    protected $table = 'stage_issues';

    protected $fillable = [
        'work_order_stage_id',
        'work_order_id',
        'defect_code',
        'severity',
        'description',
        'photo_path',
        'status',
        'ai_analysis',
        'suggested_actions',
        'reported_by',
    ];

    protected function casts(): array
    {
        return [
            'ai_analysis' => 'array',
            'suggested_actions' => 'array',
        ];
    }



    public function stage(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStage::class, 'work_order_stage_id');
    }
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
