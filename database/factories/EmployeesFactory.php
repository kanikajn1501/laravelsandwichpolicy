<?php

namespace Database\Factories;
use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Employees::class; 

    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'active' => '1'
        ];
    }
}
