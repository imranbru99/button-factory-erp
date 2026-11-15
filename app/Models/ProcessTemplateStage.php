<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessTemplateStage extends Model
{
    use HasFactory;

    protected $table = 'process_template_stages';

    protected $fillable = [
        'process_template_id',
        'code',
        'name',
        'sequence',
        'minutes_per_thousand',
        'work_center_code',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'minutes_per_thousand' => 'decimal:2',
        ];
    }



    public function template(): BelongsTo
    {
        return $this->belongsTo(ProcessTemplate::class, 'process_template_id');
    }
}
