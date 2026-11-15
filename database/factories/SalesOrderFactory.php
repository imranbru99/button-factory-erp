<?php

namespace Database\Factories;

use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SalesOrder> */
class SalesOrderFactory extends Factory
{
    protected $model = SalesOrder::class;

    public function definition(): array
    {
        return [
            'status' => fake()->unique()->lexify('sa????'),
            'strategy' => fake()->unique()->lexify('sa????'),
            'ordered_on' => now()->toDateString(),
            'currency' => fake()->unique()->lexify('sa????'),
        ];
    }
}
