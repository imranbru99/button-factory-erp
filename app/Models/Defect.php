<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Defect extends Model
{
    use HasFactory;

    protected $table = 'defects';

    protected $fillable = [
        'inspection_id',
        'defect_code',
        'qty',
        'photo_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:3',
        ];
    }



    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class, 'inspection_id');
    }
}
