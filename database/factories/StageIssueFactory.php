<?php

namespace Database\Factories;

use App\Models\StageIssue;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<StageIssue> */
class StageIssueFactory extends Factory
{
    protected $model = StageIssue::class;

    public function definition(): array
    {
        return [
            'defect_code' => fake()->unique()->lexify('st????'),
            'severity' => fake()->unique()->lexify('st????'),
            'description' => 'Factory note',
            'status' => fake()->unique()->lexify('st????'),
        ];
    }
}
