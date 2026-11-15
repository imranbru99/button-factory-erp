<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpcSample extends Model
{
    use HasFactory;

    protected $table = 'spc_samples';

    protected $fillable = [
        'work_order_stage_id',
        'characteristic',
        'value',
        'sampled_at',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:4',
            'sampled_at' => 'datetime',
        ];
    }



    public function stage(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStage::class, 'work_order_stage_id');
    }
}
