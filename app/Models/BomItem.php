<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BomItem extends Model
{
    use HasFactory;

    protected $table = 'bom_items';

    protected $fillable = [
        'bom_id',
        'parent_id',
        'component_type',
        'material_id',
        'component_product_id',
        'quantity',
        'uom',
        'scrap_factor',
        'consume_at_stage',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'scrap_factor' => 'decimal:4',
            'level' => 'integer',
        ];
    }



    public function children(): HasMany
    {
        return $this->hasMany(BomItem::class, 'parent_id');
    }
    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class, 'bom_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BomItem::class, 'parent_id');
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
    public function componentProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }
}
