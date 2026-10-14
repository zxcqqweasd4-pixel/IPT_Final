<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory()->count(10)->sequence(
            ['first_name' => 'Liam', 'last_name' => 'Santos'],
            ['first_name' => 'Sofia', 'last_name' => 'Reyes'],
            ['first_name' => 'Noah', 'last_name' => 'Garcia'],
            ['first_name' => 'Isabella', 'last_name' => 'Cruz'],
            ['first_name' => 'Ethan', 'last_name' => 'Dela Cruz'],
            ['first_name' => 'Mia', 'last_name' => 'Mendoza'],
            ['first_name' => 'Lucas', 'last_name' => 'Torres'],
            ['first_name' => 'Amara', 'last_name' => 'Flores'],
            ['first_name' => 'Gabriel', 'last_name' => 'Bautista'],
            ['first_name' => 'Ella', 'last_name' => 'Ramos'],
        )->create();
    }
}
