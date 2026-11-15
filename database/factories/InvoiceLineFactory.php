<?php

namespace Database\Factories;

use App\Models\InvoiceLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<InvoiceLine> */
class InvoiceLineFactory extends Factory
{
    protected $model = InvoiceLine::class;

    public function definition(): array
    {
        return [
            'description' => fake()->unique()->lexify('in????'),
            'qty' => 10,
            'unit_price' => 10,
        ];
    }
}
