<?php

namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        Instructor::factory()->count(5)->sequence(
            ['first_name' => 'Ramon', 'last_name' => 'Santos'],
            ['first_name' => 'Maria', 'last_name' => 'Reyes'],
            ['first_name' => 'Jose', 'last_name' => 'Garcia'],
            ['first_name' => 'Ana', 'last_name' => 'Cruz'],
            ['first_name' => 'Pedro', 'last_name' => 'Dela Cruz'],
        )->create();
    }
}
