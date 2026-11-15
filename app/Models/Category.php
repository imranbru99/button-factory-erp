<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Category extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'slug_bn',
        'parent_id',
        'description',
    ];

    protected function casts(): array
    {
        return [];
    }


    public function getSlugOptions(): array
    {
        return [
            SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->usingLanguage('en')->usingMode('ascii_only')->useAsRouteKey(false),
            SlugOptions::create()->generateSlugsFrom(fn () => $this->name_bn ?: $this->name)->saveSlugsTo('slug_bn')->usingLanguage('bn')->usingMode('preserve_unicode')->useAsRouteKey(false),
        ];
    }


    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
