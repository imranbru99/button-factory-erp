<?php

namespace Database\Factories;

use App\Models\ProcessTemplateStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProcessTemplateStage> */
class ProcessTemplateStageFactory extends Factory
{
    protected $model = ProcessTemplateStage::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('pr????'),
            'name' => fake()->unique()->lexify('pr????'),
            'sequence' => 10,
            'minutes_per_thousand' => 10,
        ];
    }
}
