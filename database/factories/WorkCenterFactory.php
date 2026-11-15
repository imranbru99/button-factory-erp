<?php

namespace Database\Factories;

use App\Models\WorkCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<WorkCenter> */
class WorkCenterFactory extends Factory
{
    protected $model = WorkCenter::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('wo????'),
            'name' => fake()->unique()->lexify('wo????'),
            'hours_per_day' => 10,
        ];
    }
}
