<?php

namespace Database\Factories;

use App\Models\Defect;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Defect> */
class DefectFactory extends Factory
{
    protected $model = Defect::class;

    public function definition(): array
    {
        return [
            'defect_code' => fake()->unique()->lexify('de????'),
            'qty' => 10,
        ];
    }
}
