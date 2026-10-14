<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Basic Road Safety', 'description' => 'Classroom instruction on road signs, traffic laws, and safe driving habits.', 'course_type' => 'theory', 'duration_hours' => 8, 'fee' => 2500],
            ['name' => 'Student Permit Preparation', 'description' => 'Theory lessons to prepare learners for the student permit examination.', 'course_type' => 'theory', 'duration_hours' => 6, 'fee' => 1800],
            ['name' => 'Beginner Practical Driving', 'description' => 'Hands-on training for new drivers in a supervised vehicle.', 'course_type' => 'practical', 'duration_hours' => 10, 'fee' => 8500],
            ['name' => 'Defensive Driving', 'description' => 'Practical hazard awareness, risk reduction, and defensive driving skills.', 'course_type' => 'practical', 'duration_hours' => 8, 'fee' => 7000],
            ['name' => 'Motorcycle Fundamentals', 'description' => 'Motorcycle controls, balance, safe maneuvering, and road awareness.', 'course_type' => 'practical', 'duration_hours' => 8, 'fee' => 6500],
        ] as $course) {
            Course::create($course);
        }
    }
}
