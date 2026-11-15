<?php

namespace Database\Factories;

use App\Models\BomItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<BomItem> */
class BomItemFactory extends Factory
{
    protected $model = BomItem::class;

    public function definition(): array
    {
        return [
            'component_type' => fake()->unique()->lexify('bo????'),
            'quantity' => 10,
            'uom' => fake()->unique()->lexify('bo????'),
        ];
    }
}
