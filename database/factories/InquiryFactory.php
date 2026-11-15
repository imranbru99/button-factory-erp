<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Inquiry> */
class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->lexify('in????'),
            'email' => fake()->unique()->lexify('in????'),
            'quantity' => 10,
            'message' => 'Factory note',
            'status' => fake()->unique()->lexify('in????'),
        ];
    }
}
