<?php

namespace Database\Factories;

use App\Models\Inspection;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Inspection> */
class InspectionFactory extends Factory
{
    protected $model = Inspection::class;

    public function definition(): array
    {
        return [
            'type' => fake()->unique()->lexify('in????'),
            'result' => fake()->unique()->lexify('in????'),
        ];
    }
}
