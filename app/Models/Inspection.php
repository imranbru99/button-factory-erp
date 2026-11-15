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

class Inspection extends Model
{
    use HasFactory, Auditable, HasDocumentNumber;

    protected $table = 'inspections';

    protected $fillable = [
        'number',
        'type',
        'inspectable_type',
        'inspectable_id',
        'result',
        'inspector_id',
        'sampled_qty',
        'rejected_qty',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'inspectable_id' => 'integer',
            'sampled_qty' => 'decimal:3',
            'rejected_qty' => 'decimal:3',
        ];
    }

    public function documentPrefix(): string
    {
        return 'QC';
    }



    public function defects(): HasMany
    {
        return $this->hasMany(Defect::class, 'inspection_id');
    }
    public function ncr(): HasOne
    {
        return $this->hasOne(Ncr::class, 'inspection_id');
    }
    public function inspectable(): MorphTo
    {
        return $this->morphTo();
    }
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}
