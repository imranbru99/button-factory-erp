<?php

namespace Database\Factories;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Machine> */
class MachineFactory extends Factory
{
    protected $model = Machine::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('ma????'),
            'name' => fake()->unique()->lexify('ma????'),
            'machine_type' => fake()->unique()->lexify('ma????'),
            'status' => fake()->unique()->lexify('ma????'),
            'capacity_per_hour' => 10,
            'hourly_rate' => 10,
        ];
    }
}
