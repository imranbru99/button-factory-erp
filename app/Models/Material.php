<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'sku',
        'name',
        'category',
        'uom',
        'valuation_method',
        'min_qty',
        'max_qty',
        'reorder_qty',
        'standard_cost',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_qty' => 'decimal:3',
            'max_qty' => 'decimal:3',
            'reorder_qty' => 'decimal:3',
            'standard_cost' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }


    public function onHand(): float
    {
        return (float) $this->lots()->sum('qty_on_hand');
    }

    public function needsReorder(): bool
    {
        return $this->is_active !== false && $this->onHand() <= (float) $this->min_qty;
    }


    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class, 'material_id');
    }
    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'material_id');
    }
}
