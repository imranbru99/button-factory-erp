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

class Delivery extends Model
{
    use HasFactory, HasDocumentNumber;

    protected $table = 'deliveries';

    protected $fillable = [
        'number',
        'sales_order_id',
        'status',
        'shipped_on',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'shipped_on' => 'date',
        ];
    }

    public function documentPrefix(): string
    {
        return 'DN';
    }



    public function lines(): HasMany
    {
        return $this->hasMany(DeliveryLine::class, 'delivery_id');
    }
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
}
