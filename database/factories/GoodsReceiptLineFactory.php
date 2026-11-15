<?php

namespace Database\Factories;

use App\Models\GoodsReceiptLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GoodsReceiptLine> */
class GoodsReceiptLineFactory extends Factory
{
    protected $model = GoodsReceiptLine::class;

    public function definition(): array
    {
        return [
            'qty' => 10,
            'unit_cost' => 10,
        ];
    }
}
