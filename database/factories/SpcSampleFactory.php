<?php

namespace Database\Factories;

use App\Models\SpcSample;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SpcSample> */
class SpcSampleFactory extends Factory
{
    protected $model = SpcSample::class;

    public function definition(): array
    {
        return [
            'characteristic' => fake()->unique()->lexify('sp????'),
            'value' => 10,
            'sampled_at' => now(),
        ];
    }
}
