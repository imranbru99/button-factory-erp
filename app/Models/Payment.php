<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payable_type',
        'payable_id',
        'direction',
        'amount',
        'paid_on',
        'method',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'payable_id' => 'integer',
            'amount' => 'decimal:2',
            'paid_on' => 'date',
        ];
    }



    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
