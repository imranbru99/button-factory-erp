<?php

namespace Database\Factories;

use App\Models\GoodsReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<GoodsReceipt> */
class GoodsReceiptFactory extends Factory
{
    protected $model = GoodsReceipt::class;

    public function definition(): array
    {
        return [
            'received_on' => now()->toDateString(),
        ];
    }
}
