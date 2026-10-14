<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::query()->pluck('id')->all();
        $instructors = Instructor::query()->pluck('id')->all();
        $vehicles = Vehicle::query()->where('status', 'available')->pluck('id')->all();
        $courses = Course::query()->get();

        for ($index = 0; $index < 25; $index++) {
            $course = $courses[$index % $courses->count()];
            $date = today()->addDays($index + 1);
            $startHour = 8 + (($index % 5) * 2);

            Lesson::create([
                'student_id' => $students[$index % count($students)],
                'instructor_id' => $instructors[$index % count($instructors)],
                'vehicle_id' => $course->course_type === 'theory'
                    ? null
                    : $vehicles[$index % count($vehicles)],
                'course_id' => $course->id,
                'lesson_date' => $date->toDateString(),
                'start_time' => sprintf('%02d:00', $startHour),
                'end_time' => sprintf('%02d:00', $startHour + 1),
                'status' => 'scheduled',
                'remarks' => 'Seeded practice and instruction session.',
            ]);
        }
    }
}
