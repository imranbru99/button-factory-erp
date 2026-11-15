<?php

namespace Database\Factories;

use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Quotation> */
class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition(): array
    {
        return [
            'status' => fake()->unique()->lexify('qu????'),
            'currency' => fake()->unique()->lexify('qu????'),
        ];
    }
}
