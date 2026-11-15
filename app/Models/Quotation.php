<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasDocumentNumber;

class Quotation extends Model
{
    use HasFactory, HasDocumentNumber;

    protected $table = 'quotations';

    protected $fillable = [
        'number',
        'customer_id',
        'inquiry_id',
        'status',
        'valid_until',
        'currency',
        'subtotal',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
            'subtotal' => 'decimal:2',
        ];
    }

    public function documentPrefix(): string
    {
        return 'QT';
    }



    public function lines(): HasMany
    {
        return $this->hasMany(QuotationLine::class, 'quotation_id');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function inquiry(): BelongsTo
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id');
    }
}
