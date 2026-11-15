<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormulaComponent extends Model
{
    use HasFactory;

    protected $table = 'formula_components';

    protected $fillable = [
        'formula_id',
        'material_id',
        'role',
        'percentage',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:3',
        ];
    }



    public function formula(): BelongsTo
    {
        return $this->belongsTo(Formula::class, 'formula_id');
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
