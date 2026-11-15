<?php

namespace Database\Factories;

use App\Models\FormulaComponent;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<FormulaComponent> */
class FormulaComponentFactory extends Factory
{
    protected $model = FormulaComponent::class;

    public function definition(): array
    {
        return [
            'role' => fake()->unique()->lexify('fo????'),
            'percentage' => 10,
        ];
    }
}
