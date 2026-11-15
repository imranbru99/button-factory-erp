<?php

namespace Database\Factories;

use App\Models\Formula;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Formula> */
class FormulaFactory extends Factory
{
    protected $model = Formula::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('fo????'),
            'name' => fake()->unique()->lexify('fo????'),
            'version' => 10,
            'status' => fake()->unique()->lexify('fo????'),
        ];
    }
}
