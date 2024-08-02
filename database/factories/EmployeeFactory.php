<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'employee_id' => $this->faker->unique()->word(),
            'user_name' => $this->faker->userName(),
            'name_prefix' => $this->faker->name(),
            'first_name' => $this->faker->firstName(),
            'middle_initial' => $this->faker->word(),
            'last_name' => $this->faker->lastName(),
            'gender' => $this->faker->word(),
            'email' => $this->faker->unique()->safeEmail(),
            'date_of_birth' => Carbon::now(),
            'time_of_birth' => Carbon::now(),
            'age_in_years' => $this->faker->randomNumber(),
            'date_of_joining' => Carbon::now(),
            'age_in_company' => $this->faker->company(),
            'phone_number' => $this->faker->phoneNumber(),
            'place_name' => $this->faker->name(),
            'county' => $this->faker->word(),
            'city' => $this->faker->city(),
            'zip' => $this->faker->postcode(),
            'region' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
