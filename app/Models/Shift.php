<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'name',
        'starts_at',
        'ends_at',
        'break_minutes',
    ];

    protected function casts(): array
    {
        return [
            'break_minutes' => 'integer',
        ];
    }



    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'shift_id');
    }
}
