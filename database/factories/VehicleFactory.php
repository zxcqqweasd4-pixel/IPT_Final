<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'plate_number' => fake()->unique()->bothify('???-####'),
            'model' => fake()->randomElement(['Toyota Vios', 'Honda City', 'Mitsubishi Mirage', 'Toyota Innova', 'Honda Click']),
            'type' => fake()->randomElement(['sedan', 'SUV', 'motorcycle', 'truck']),
            'transmission' => fake()->randomElement(['manual', 'automatic']),
            'status' => 'available',
        ];
    }
}
