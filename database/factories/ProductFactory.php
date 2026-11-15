<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->lexify('pr????'),
            'name' => fake()->unique()->lexify('pr????'),
            'button_family' => fake()->unique()->lexify('pr????'),
            'size_ligne' => 10,
            'thickness_mm' => 10,
            'hole_type' => fake()->unique()->lexify('pr????'),
            'design_pattern' => fake()->unique()->lexify('pr????'),
            'finish' => fake()->unique()->lexify('pr????'),
            'color' => fake()->unique()->lexify('pr????'),
            'strategy' => fake()->unique()->lexify('pr????'),
            'standard_cost' => 10,
            'list_price' => 10,
            'is_published' => true,
        ];
    }
}
