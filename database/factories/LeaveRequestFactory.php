<?php

namespace Database\Factories;

use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<LeaveRequest> */
class LeaveRequestFactory extends Factory
{
    protected $model = LeaveRequest::class;

    public function definition(): array
    {
        return [
            'type' => fake()->unique()->lexify('le????'),
            'starts_on' => now()->toDateString(),
            'ends_on' => now()->toDateString(),
            'status' => fake()->unique()->lexify('le????'),
        ];
    }
}
