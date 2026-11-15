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

class GoodsReceipt extends Model
{
    use HasFactory, Auditable, HasDocumentNumber;

    protected $table = 'goods_receipts';

    protected $fillable = [
        'number',
        'purchase_order_id',
        'warehouse_id',
        'received_on',
        'invoice_path',
        'ocr_payload',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'received_on' => 'date',
            'ocr_payload' => 'array',
        ];
    }

    public function documentPrefix(): string
    {
        return 'GR';
    }



    public function lines(): HasMany
    {
        return $this->hasMany(GoodsReceiptLine::class, 'goods_receipt_id');
    }
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
