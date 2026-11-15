<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceBreak extends Model
{
    use HasFactory;

    protected $table = 'price_breaks';

    protected $fillable = [
        'product_id',
        'min_qty',
        'finish',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'min_qty' => 'integer',
            'unit_price' => 'decimal:4',
        ];
    }



    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
