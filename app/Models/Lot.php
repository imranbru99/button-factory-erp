<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends Model
{
    use HasFactory;

    protected $table = 'lots';

    protected $fillable = [
        'material_id',
        'warehouse_id',
        'bin_id',
        'supplier_id',
        'lot_number',
        'qty_on_hand',
        'unit_cost',
        'received_at',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'qty_on_hand' => 'decimal:3',
            'unit_cost' => 'decimal:4',
            'received_at' => 'datetime',
            'expires_at' => 'date',
        ];
    }



    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function bin(): BelongsTo
    {
        return $this->belongsTo(Bin::class, 'bin_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
