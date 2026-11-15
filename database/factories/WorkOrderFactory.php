<?php

namespace Database\Factories;

use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<WorkOrder> */
class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        return [
            'is_rework' => true,
            'qty_planned' => 10,
            'status' => fake()->unique()->lexify('wo????'),
            'strategy' => fake()->unique()->lexify('wo????'),
        ];
    }
}
