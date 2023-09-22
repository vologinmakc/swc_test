<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'login'             => $this->faker->unique()->userName,
            'first_name'        => $this->faker->firstName,
            'last_name'         => $this->faker->lastName,
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'registration_date' => now(),
            'birth_date'        => $this->faker->date()
        ];
    }
}
