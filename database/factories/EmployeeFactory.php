<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Employee> */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'employee_code' => fake()->unique()->lexify('em????'),
            'name' => fake()->unique()->lexify('em????'),
            'designation' => fake()->unique()->lexify('em????'),
            'hourly_rate' => 10,
            'skill_level' => fake()->unique()->lexify('em????'),
            'hired_on' => now()->toDateString(),
            'is_active' => true,
        ];
    }
}
