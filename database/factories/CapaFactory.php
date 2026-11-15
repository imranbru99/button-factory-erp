<?php

namespace Database\Factories;

use App\Models\Capa;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Capa> */
class CapaFactory extends Factory
{
    protected $model = Capa::class;

    public function definition(): array
    {
        return [
            'action' => 'Factory note',
            'status' => fake()->unique()->lexify('ca????'),
        ];
    }
}
