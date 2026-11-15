<?php

namespace Database\Factories;

use App\Models\MaintenanceTicket;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MaintenanceTicket> */
class MaintenanceTicketFactory extends Factory
{
    protected $model = MaintenanceTicket::class;

    public function definition(): array
    {
        return [
            'priority' => fake()->unique()->lexify('ma????'),
            'status' => fake()->unique()->lexify('ma????'),
            'title' => fake()->unique()->lexify('ma????'),
        ];
    }
}
