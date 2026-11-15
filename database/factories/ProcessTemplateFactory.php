<?php

namespace Database\Factories;

use App\Models\ProcessTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProcessTemplate> */
class ProcessTemplateFactory extends Factory
{
    protected $model = ProcessTemplate::class;

    public function definition(): array
    {
        return [
            'button_family' => fake()->unique()->lexify('pr????'),
            'name' => fake()->unique()->lexify('pr????'),
        ];
    }
}
