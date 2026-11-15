<?php

namespace Database\Factories;

use App\Models\Bin;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Bin> */
class BinFactory extends Factory
{
    protected $model = Bin::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('bi????'),
            'name' => fake()->unique()->lexify('bi????'),
        ];
    }
}
