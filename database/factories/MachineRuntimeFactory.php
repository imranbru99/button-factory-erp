<?php

namespace Database\Factories;

use App\Models\MachineRuntime;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MachineRuntime> */
class MachineRuntimeFactory extends Factory
{
    protected $model = MachineRuntime::class;

    public function definition(): array
    {
        return [
            'started_at' => now(),
        ];
    }
}
