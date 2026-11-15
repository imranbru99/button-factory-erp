<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capa extends Model
{
    use HasFactory;

    protected $table = 'capas';

    protected $fillable = [
        'ncr_id',
        'action',
        'owner_id',
        'due_on',
        'status',
        'effectiveness_notes',
    ];

    protected function casts(): array
    {
        return [
            'due_on' => 'date',
        ];
    }



    public function ncr(): BelongsTo
    {
        return $this->belongsTo(Ncr::class, 'ncr_id');
    }
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
