<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\Auditable;
use App\Models\Concerns\HasDocumentNumber;

class SalesOrder extends Model
{
    use HasFactory, Auditable, HasDocumentNumber;

    protected $table = 'sales_orders';

    protected $fillable = [
        'number',
        'customer_id',
        'quotation_id',
        'status',
        'strategy',
        'ordered_on',
        'promised_on',
        'currency',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'ordered_on' => 'date',
            'promised_on' => 'date',
        ];
    }

    public function documentPrefix(): string
    {
        return 'SO';
    }



    public function lines(): HasMany
    {
        return $this->hasMany(SalesOrderLine::class, 'sales_order_id');
    }
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'sales_order_id');
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
