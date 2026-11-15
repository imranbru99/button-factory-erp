<?php

namespace Database\Factories;

use App\Models\AutomationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AutomationLog> */
class AutomationLogFactory extends Factory
{
    protected $model = AutomationLog::class;

    public function definition(): array
    {
        return [
            'trigger' => fake()->unique()->lexify('au????'),
            'rule' => fake()->unique()->lexify('au????'),
            'action' => fake()->unique()->lexify('au????'),
        ];
    }
}
