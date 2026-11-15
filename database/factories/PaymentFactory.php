<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Payment> */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'payable_type' => fake()->unique()->lexify('pa????'),
            'payable_id' => 10,
            'direction' => fake()->unique()->lexify('pa????'),
            'amount' => 10,
            'paid_on' => now()->toDateString(),
            'method' => fake()->unique()->lexify('pa????'),
        ];
    }
}
