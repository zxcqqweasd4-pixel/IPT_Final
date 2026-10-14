<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DrivingSchoolTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_and_all_resource_indexes_are_available(): void
    {
        foreach (['students', 'instructors', 'vehicles', 'courses', 'lessons'] as $table) {
            $this->assertTrue(Schema::hasTable($table));
        }

        $this->get('/')->assertOk()->assertSee('Campus dashboard');
        foreach (['students', 'instructors', 'vehicles', 'courses', 'lessons'] as $resource) {
            $this->get('/'.$resource)->assertOk();
        }
    }

    public function test_student_can_be_created_and_updated(): void
    {
        $payload = [
            'first_name' => 'Alex',
            'last_name' => 'Santos',
            'email' => 'alex@example.test',
            'phone' => '+639171234567',
            'address' => 'Quezon City',
            'birthdate' => '2000-04-12',
            'license_status' => 'student_permit',
        ];

        $this->post('/students', $payload)
            ->assertRedirect();
        $student = Student::where('email', $payload['email'])->firstOrFail();
        $this->assertDatabaseHas('students', ['id' => $student->id, 'first_name' => 'Alex']);

        $this->put('/students/'.$student->id, array_merge($payload, ['first_name' => 'Alexandra']))
            ->assertRedirect();
        $this->assertDatabaseHas('students', ['id' => $student->id, 'first_name' => 'Alexandra']);
    }

    public function test_parent_with_lessons_cannot_be_deleted(): void
    {
        $student = Student::factory()->create();
        $lesson = Lesson::factory()->create(['student_id' => $student->id]);

        $this->delete('/students/'.$student->id)
            ->assertRedirect(route('students.index'))
            ->assertSessionHas('error', 'This student still has lessons and cannot be deleted.');

        $this->assertDatabaseHas('students', ['id' => $student->id]);
        $this->assertDatabaseHas('lessons', ['id' => $lesson->id]);
    }

    public function test_create_edit_and_show_pages_render_for_each_resource(): void
    {
        $student = Student::factory()->create();
        $instructor = Instructor::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $course = Course::factory()->create(['course_type' => 'practical']);
        $lesson = Lesson::factory()->create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'vehicle_id' => $vehicle->id,
            'course_id' => $course->id,
            'lesson_date' => now()->addDays(10)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        foreach ([
            'students' => $student,
            'instructors' => $instructor,
            'vehicles' => $vehicle,
            'courses' => $course,
            'lessons' => $lesson,
        ] as $resource => $record) {
            $this->get('/'.$resource.'/create')->assertOk();
            $this->get('/'.$resource.'/'.$record->id.'/edit')->assertOk();
            $this->get('/'.$resource.'/'.$record->id)->assertOk();
        }

        $this->put('/lessons/'.$lesson->id, [
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'vehicle_id' => $vehicle->id,
            'course_id' => $course->id,
            'lesson_date' => $lesson->lesson_date->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'scheduled',
        ])->assertRedirect(route('lessons.show', $lesson));
    }

    public function test_practical_lesson_requires_vehicle_and_overlapping_lessons_are_rejected(): void
    {
        $student = Student::factory()->create();
        $instructor = Instructor::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $course = Course::factory()->create(['course_type' => 'practical']);
        $date = now()->addDays(3)->toDateString();
        $payload = [
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'course_id' => $course->id,
            'lesson_date' => $date,
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'scheduled',
        ];

        $this->from('/lessons/create')->post('/lessons', $payload)
            ->assertRedirect('/lessons/create')
            ->assertSessionHasErrors('vehicle_id');

        Lesson::factory()->create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'vehicle_id' => $vehicle->id,
            'course_id' => $course->id,
            'lesson_date' => $date,
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);

        $this->from('/lessons/create')->post('/lessons', array_merge($payload, ['vehicle_id' => $vehicle->id]))
            ->assertRedirect('/lessons/create')
            ->assertSessionHasErrors(['student_id', 'instructor_id', 'vehicle_id']);

        $this->post('/lessons', array_merge($payload, [
            'vehicle_id' => $vehicle->id,
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]))->assertRedirect();
    }
}
