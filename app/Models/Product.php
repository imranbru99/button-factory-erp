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
use ImranDev\UniversalSlug\SlugOptions;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

class Product extends Model
{
    use HasFactory, Auditable, HasUniversalSlug;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'name_bn',
        'slug',
        'slug_bn',
        'button_family',
        'size_ligne',
        'thickness_mm',
        'hole_type',
        'design_pattern',
        'finish',
        'color',
        'color_code',
        'material_label',
        'strategy',
        'standard_cost',
        'list_price',
        'min_stock',
        'reorder_qty',
        'is_published',
        'image_path',
        'excerpt',
        'description',
        'description_bn',
        'meta_title',
        'meta_description',
        'attributes',
        'tags',
        'faq',
        'care_instructions',
        'size_guide',
        'gtin',
        'mpn',
        'json_ld',
        'image_alt',
        'translations',
        'locale',
    ];

    protected function casts(): array
    {
        return [
            'size_ligne' => 'integer',
            'thickness_mm' => 'decimal:2',
            'standard_cost' => 'decimal:4',
            'list_price' => 'decimal:4',
            'min_stock' => 'decimal:3',
            'reorder_qty' => 'decimal:3',
            'is_published' => 'boolean',
            'attributes' => 'array',
            'tags' => 'array',
            'faq' => 'array',
            'size_guide' => 'array',
            'json_ld' => 'array',
            'translations' => 'array',
        ];
    }


    public function getSlugOptions(): array
    {
        return [
            SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug')->usingLanguage('en')->usingMode('ascii_only')->useAsRouteKey(false),
            SlugOptions::create()->generateSlugsFrom(fn () => $this->name_bn ?: $this->name)->saveSlugsTo('slug_bn')->usingLanguage('bn')->usingMode('preserve_unicode')->useAsRouteKey(false),
        ];
    }

    public function priceFor(int $qty, ?string $finish = null): string
    {
        $break = $this->priceBreaks()
            ->where('min_qty', '<=', $qty)
            ->when($finish, function ($query) use ($finish) {
                $query->where(function ($inner) use ($finish) {
                    $inner->whereNull('finish')->orWhere('finish', $finish);
                });
            })
            ->orderByDesc('min_qty')
            ->first();

        return (string) ($break->unit_price ?? $this->list_price);
    }

    public function onHand(): float
    {
        return (float) $this->stocks()->sum('qty');
    }

    public function activeBom(): ?Bom
    {
        return $this->boms()->where('status', 'active')->orderByDesc('version')->first();
    }

    public function activeFormula(): ?Formula
    {
        return $this->formulas()->where('status', 'active')->orderByDesc('version')->first();
    }

    /** @return array<string, mixed> */
    public function jsonLdPayload(): array
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->excerpt ?: $this->description,
            'material' => $this->material_label,
            'color' => $this->color,
            'brand' => ['@type' => 'Brand', 'name' => config('factory.name')],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => config('factory.currency'),
                'price' => (string) $this->list_price,
                'availability' => 'https://schema.org/PreOrder',
            ],
        ];

        if (is_array($this->json_ld)) {
            foreach ($this->json_ld as $key => $value) {
                if ($value !== null && $value !== '') {
                    $payload[$key] = $value;
                }
            }
        }

        if (blank($payload['description'] ?? null)) {
            $payload['description'] = $this->excerpt ?: $this->description;
        }

        return $payload;
    }


    public function formulas(): HasMany
    {
        return $this->hasMany(Formula::class, 'product_id');
    }
    public function boms(): HasMany
    {
        return $this->hasMany(Bom::class, 'product_id');
    }
    public function priceBreaks(): HasMany
    {
        return $this->hasMany(PriceBreak::class, 'product_id');
    }
    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }
    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class, 'product_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
