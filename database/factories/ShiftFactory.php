<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Shift> */
class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->lexify('sh????'),
            'starts_at' => fake()->unique()->lexify('sh????'),
            'ends_at' => fake()->unique()->lexify('sh????'),
            'break_minutes' => 10,
        ];
    }
}
