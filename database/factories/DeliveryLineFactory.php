<?php

namespace Database\Factories;

use App\Models\DeliveryLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<DeliveryLine> */
class DeliveryLineFactory extends Factory
{
    protected $model = DeliveryLine::class;

    public function definition(): array
    {
        return [
            'qty' => 10,
        ];
    }
}
