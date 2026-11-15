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

class Bom extends Model
{
    use HasFactory, Auditable;

    protected $table = 'boms';

    protected $fillable = [
        'product_id',
        'version',
        'status',
        'notes',
        'supersedes_id',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
        ];
    }



    public function items(): HasMany
    {
        return $this->hasMany(BomItem::class, 'bom_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function supersedes(): BelongsTo
    {
        return $this->belongsTo(Bom::class, 'supersedes_id');
    }
}
