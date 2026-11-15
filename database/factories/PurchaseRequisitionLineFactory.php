<?php

namespace Database\Factories;

use App\Models\PurchaseRequisitionLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PurchaseRequisitionLine> */
class PurchaseRequisitionLineFactory extends Factory
{
    protected $model = PurchaseRequisitionLine::class;

    public function definition(): array
    {
        return [
            'qty' => 10,
        ];
    }
}
