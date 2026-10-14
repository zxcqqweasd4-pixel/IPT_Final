<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        $course = Course::query()->inRandomOrder()->first();
        $course ??= Course::factory()->create();

        return [
            'student_id' => Student::query()->inRandomOrder()->value('id') ?? Student::factory(),
            'instructor_id' => Instructor::query()->inRandomOrder()->value('id') ?? Instructor::factory(),
            'vehicle_id' => $course->course_type === 'theory'
                ? null
                : (Vehicle::query()->inRandomOrder()->value('id') ?? Vehicle::factory()),
            'course_id' => $course->id,
            'lesson_date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'scheduled',
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}
