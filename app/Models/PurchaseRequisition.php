<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasDocumentNumber;

class PurchaseRequisition extends Model
{
    use HasFactory, HasDocumentNumber;

    protected $table = 'purchase_requisitions';

    protected $fillable = [
        'number',
        'status',
        'source',
        'requested_by',
        'needed_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'needed_by' => 'date',
        ];
    }

    public function documentPrefix(): string
    {
        return 'PR';
    }



    public function lines(): HasMany
    {
        return $this->hasMany(PurchaseRequisitionLine::class, 'purchase_requisition_id');
    }
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
