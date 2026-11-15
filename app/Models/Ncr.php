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

class Ncr extends Model
{
    use HasFactory, Auditable, HasDocumentNumber;

    protected $table = 'ncrs';

    protected $fillable = [
        'number',
        'inspection_id',
        'work_order_id',
        'status',
        'description',
        'disposition',
        'opened_by',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function documentPrefix(): string
    {
        return 'NCR';
    }



    public function capas(): HasMany
    {
        return $this->hasMany(Capa::class, 'ncr_id');
    }
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class, 'inspection_id');
    }
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }
    public function opener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }
}
