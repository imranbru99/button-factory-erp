<?php

namespace Database\Factories;

use App\Models\Bom;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Bom> */
class BomFactory extends Factory
{
    protected $model = Bom::class;

    public function definition(): array
    {
        return [
            'version' => 10,
            'status' => fake()->unique()->lexify('bo????'),
        ];
    }
}
