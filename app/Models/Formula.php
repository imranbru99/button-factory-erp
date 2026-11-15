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

class Formula extends Model
{
    use HasFactory, Auditable;

    protected $table = 'formulas';

    protected $fillable = [
        'product_id',
        'code',
        'name',
        'version',
        'status',
        'notes',
        'effective_from',
        'supersedes_id',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'effective_from' => 'date',
        ];
    }



    public function components(): HasMany
    {
        return $this->hasMany(FormulaComponent::class, 'formula_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function supersedes(): BelongsTo
    {
        return $this->belongsTo(Formula::class, 'supersedes_id');
    }
}
