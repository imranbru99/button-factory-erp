<?php

namespace Database\Factories;

use App\Models\SalesOrderLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SalesOrderLine> */
class SalesOrderLineFactory extends Factory
{
    protected $model = SalesOrderLine::class;

    public function definition(): array
    {
        return [
            'qty' => 10,
            'unit_price' => 10,
        ];
    }
}
