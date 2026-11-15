<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrderLine extends Model
{
    use HasFactory;

    protected $table = 'sales_order_lines';

    protected $fillable = [
        'sales_order_id',
        'product_id',
        'qty',
        'qty_delivered',
        'unit_price',
        'finish',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'qty_delivered' => 'integer',
            'unit_price' => 'decimal:4',
        ];
    }



    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'sales_order_line_id');
    }
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
