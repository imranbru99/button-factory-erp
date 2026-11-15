<?php

namespace Database\Factories;

use App\Models\WorkOrderStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<WorkOrderStage> */
class WorkOrderStageFactory extends Factory
{
    protected $model = WorkOrderStage::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('wo????'),
            'name' => fake()->unique()->lexify('wo????'),
            'sequence' => 10,
            'status' => fake()->unique()->lexify('wo????'),
        ];
    }
}
