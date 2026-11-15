<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessTemplate extends Model
{
    use HasFactory;

    protected $table = 'process_templates';

    protected $fillable = [
        'button_family',
        'name',
    ];

    protected function casts(): array
    {
        return [];
    }



    public function stages(): HasMany
    {
        return $this->hasMany(ProcessTemplateStage::class, 'process_template_id');
    }
}
