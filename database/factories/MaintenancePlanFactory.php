<?php

namespace Database\Factories;

use App\Models\MaintenancePlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MaintenancePlan> */
class MaintenancePlanFactory extends Factory
{
    protected $model = MaintenancePlan::class;

    public function definition(): array
    {
        return [
            'task' => fake()->unique()->lexify('ma????'),
            'interval_days' => 10,
            'next_due_on' => now()->toDateString(),
        ];
    }
}
