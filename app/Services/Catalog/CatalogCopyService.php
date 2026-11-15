<?php

namespace App\Services\Catalog;

use App\Models\Product;
use ImranDevBd\AiCatalog\Facades\AiCatalog;
use Throwable;

class CatalogCopyService
{
    public function generate(Product $product): Product
    {
        $local = $this->localCopy($product);

        if (! config('factory.ai.enabled')) {
            $product->update($local);

            return $product->refresh();
        }

        try {
            $keyword = trim("{$product->name} {$product->size_ligne}L {$product->button_family} {$product->finish} {$product->color} button");
            $generation = AiCatalog::pending()
                ->fromKeyword($keyword)
                ->forProduct($product)
                ->locale('en')
                ->locales(['en', 'bn'])
                ->tone('professional')
                ->generate();

            AiCatalog::approve($generation);

            return $product->refresh();
        } catch (Throwable) {
            $product->update($local);

            return $product->refresh();
        }
    }

    /** @return array<string, mixed> */
    public function localCopy(Product $product): array
    {
        $size = $product->size_ligne.'L';
        $family = str_replace('_', ' ', $product->button_family);
        $excerpt = "{$size} {$product->color} {$family} button, {$product->hole_type} hole, {$product->finish} finish.";
        $description = "Made for garment factories that need a repeatable {$size} {$product->color} button. "
            ."Family: {$family}. Thickness {$product->thickness_mm} mm. Pattern: {$product->design_pattern}. "
            .'Supplied against a versioned formula and bill of materials, with stage-wise QC before packing.';
        $descriptionBn = "পোশাক কারখানার জন্য {$size} {$product->color} বোতাম। ধরন: {$family}। পুরুত্ব {$product->thickness_mm} মিমি। ফিনিশ: {$product->finish}।";

        return [
            'excerpt' => $excerpt,
            'description' => $description,
            'description_bn' => $descriptionBn,
            'meta_title' => "{$product->name} | {$size}",
            'meta_description' => $excerpt,
            'image_alt' => "{$product->color} {$size} {$family} button",
            'json_ld' => $product->jsonLdPayload(),
            'attributes' => [
                'size_ligne' => $size,
                'hole_type' => $product->hole_type,
                'finish' => $product->finish,
                'color' => $product->color,
            ],
            'tags' => [$family, $size, $product->color, $product->finish],
            'is_published' => true,
        ];
    }
}
