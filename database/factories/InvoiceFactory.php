<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Invoice> */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'status' => fake()->unique()->lexify('in????'),
            'issued_on' => now()->toDateString(),
            'currency' => fake()->unique()->lexify('in????'),
        ];
    }
}
