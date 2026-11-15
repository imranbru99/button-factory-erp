<?php

namespace Database\Factories;

use App\Models\Ncr;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Ncr> */
class NcrFactory extends Factory
{
    protected $model = Ncr::class;

    public function definition(): array
    {
        return [
            'status' => fake()->unique()->lexify('nc????'),
            'description' => 'Factory note',
        ];
    }
}
