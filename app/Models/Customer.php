<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'code',
        'name',
        'company',
        'email',
        'phone',
        'country',
        'city',
        'address',
        'payment_terms',
    ];

    protected function casts(): array
    {
        return [];
    }



    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class, 'customer_id');
    }
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }
    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }
}
