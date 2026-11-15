<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkCenter extends Model
{
    use HasFactory;

    protected $table = 'work_centers';

    protected $fillable = [
        'code',
        'name',
        'department_id',
        'hours_per_day',
    ];

    protected function casts(): array
    {
        return [
            'hours_per_day' => 'decimal:2',
        ];
    }



    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'work_center_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
