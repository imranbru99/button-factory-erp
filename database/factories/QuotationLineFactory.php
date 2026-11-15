<?php

namespace Database\Factories;

use App\Models\QuotationLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<QuotationLine> */
class QuotationLineFactory extends Factory
{
    protected $model = QuotationLine::class;

    public function definition(): array
    {
        return [
            'qty' => 10,
            'unit_price' => 10,
        ];
    }
}
