<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Supplier> */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('su????'),
            'name' => fake()->unique()->lexify('su????'),
            'lead_time_days' => 10,
        ];
    }
}
