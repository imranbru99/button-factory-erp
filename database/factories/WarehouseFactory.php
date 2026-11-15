<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Warehouse> */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('wa????'),
            'name' => fake()->unique()->lexify('wa????'),
            'kind' => fake()->unique()->lexify('wa????'),
        ];
    }
}
