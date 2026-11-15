<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Material> */
class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->lexify('ma????'),
            'name' => fake()->unique()->lexify('ma????'),
            'category' => fake()->unique()->lexify('ma????'),
            'uom' => fake()->unique()->lexify('ma????'),
            'valuation_method' => fake()->unique()->lexify('ma????'),
            'min_qty' => 10,
            'max_qty' => 10,
            'reorder_qty' => 10,
            'standard_cost' => 10,
            'is_active' => true,
        ];
    }
}
