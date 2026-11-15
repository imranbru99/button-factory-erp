<?php

namespace Database\Factories;

use App\Models\PriceBreak;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PriceBreak> */
class PriceBreakFactory extends Factory
{
    protected $model = PriceBreak::class;

    public function definition(): array
    {
        return [
            'min_qty' => 10,
            'unit_price' => 10,
        ];
    }
}
