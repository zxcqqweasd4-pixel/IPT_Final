<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['theory', 'practical']);

        return [
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->sentence(12),
            'course_type' => $type,
            'duration_hours' => fake()->numberBetween(2, 20),
            'fee' => fake()->randomFloat(2, 1000, 15000),
        ];
    }
}
