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

class PurchaseOrder extends Model
{
    use HasFactory, Auditable, HasDocumentNumber;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'number',
        'supplier_id',
        'purchase_requisition_id',
        'status',
        'ordered_on',
        'expected_on',
        'currency',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'ordered_on' => 'date',
            'expected_on' => 'date',
        ];
    }

    public function documentPrefix(): string
    {
        return 'PO';
    }



    public function lines(): HasMany
    {
        return $this->hasMany(PurchaseOrderLine::class, 'purchase_order_id');
    }
    public function receipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class, 'purchase_order_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function requisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class, 'purchase_requisition_id');
    }
}
