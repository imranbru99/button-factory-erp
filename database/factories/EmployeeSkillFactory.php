<?php

namespace Database\Factories;

use App\Models\EmployeeSkill;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<EmployeeSkill> */
class EmployeeSkillFactory extends Factory
{
    protected $model = EmployeeSkill::class;

    public function definition(): array
    {
        return [
            'skill' => fake()->unique()->lexify('em????'),
            'level' => 10,
        ];
    }
}
