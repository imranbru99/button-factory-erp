<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
        'name_bn',
    ];

    protected function casts(): array
    {
        return [];
    }



    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
    public function machines(): HasMany
    {
        return $this->hasMany(Machine::class, 'department_id');
    }
}
