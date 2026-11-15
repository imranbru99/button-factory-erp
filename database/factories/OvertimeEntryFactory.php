<?php

namespace Database\Factories;

use App\Models\OvertimeEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<OvertimeEntry> */
class OvertimeEntryFactory extends Factory
{
    protected $model = OvertimeEntry::class;

    public function definition(): array
    {
        return [
            'work_date' => now()->toDateString(),
            'hours' => 10,
            'rate_multiplier' => 10,
            'status' => fake()->unique()->lexify('ov????'),
        ];
    }
}
