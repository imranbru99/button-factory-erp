<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderLine extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_lines';

    protected $fillable = [
        'purchase_order_id',
        'material_id',
        'qty',
        'qty_received',
        'unit_cost',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:3',
            'qty_received' => 'decimal:3',
            'unit_cost' => 'decimal:4',
        ];
    }



    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
