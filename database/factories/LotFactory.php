<?php

namespace Database\Factories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Lot> */
class LotFactory extends Factory
{
    protected $model = Lot::class;

    public function definition(): array
    {
        return [
            'lot_number' => fake()->unique()->lexify('lo????'),
            'qty_on_hand' => 10,
            'unit_cost' => 10,
            'status' => fake()->unique()->lexify('lo????'),
        ];
    }
}
