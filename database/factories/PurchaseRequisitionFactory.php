<?php

namespace Database\Factories;

use App\Models\PurchaseRequisition;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PurchaseRequisition> */
class PurchaseRequisitionFactory extends Factory
{
    protected $model = PurchaseRequisition::class;

    public function definition(): array
    {
        return [
            'status' => fake()->unique()->lexify('pu????'),
            'source' => fake()->unique()->lexify('pu????'),
        ];
    }
}
