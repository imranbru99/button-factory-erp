<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseRequisitionLine extends Model
{
    use HasFactory;

    protected $table = 'purchase_requisition_lines';

    protected $fillable = [
        'purchase_requisition_id',
        'material_id',
        'qty',
        'estimated_cost',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'decimal:3',
            'estimated_cost' => 'decimal:4',
        ];
    }



    public function requisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class, 'purchase_requisition_id');
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
