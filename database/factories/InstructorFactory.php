<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+639#########'),
            'license_number' => fake()->unique()->bothify('PH-########'),
            'specialization' => fake()->randomElement(['Defensive driving', 'Motorcycle', 'Heavy vehicles', 'Road safety']),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
