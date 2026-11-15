<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'kind',
        'address',
    ];

    protected function casts(): array
    {
        return [];
    }



    public function bins(): HasMany
    {
        return $this->hasMany(Bin::class, 'warehouse_id');
    }
    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class, 'warehouse_id');
    }
}
