<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidationValidator;

class LessonController extends ResourceController
{
    protected string $modelClass = Lesson::class;

    protected string $resource = 'lessons';

    protected string $title = 'Lessons';

    protected string $singular = 'lesson';

    protected array $indexWith = ['student', 'instructor', 'vehicle', 'course'];

    protected array $showWith = ['student', 'instructor', 'vehicle', 'course'];

    protected array $fields = [
        ['name' => 'student_id', 'label' => 'Student', 'type' => 'select', 'options' => []],
        ['name' => 'instructor_id', 'label' => 'Instructor', 'type' => 'select', 'options' => []],
        ['name' => 'course_id', 'label' => 'Course', 'type' => 'select', 'options' => []],
        ['name' => 'vehicle_id', 'label' => 'Vehicle (required for practical courses)', 'type' => 'select', 'options' => [], 'nullable' => true],
        ['name' => 'lesson_date', 'label' => 'Lesson date', 'type' => 'date'],
        ['name' => 'start_time', 'label' => 'Start time', 'type' => 'time'],
        ['name' => 'end_time', 'label' => 'End time', 'type' => 'time'],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => [
            'scheduled' => 'Scheduled', 'completed' => 'Completed', 'cancelled' => 'Cancelled',
        ]],
        ['name' => 'remarks', 'label' => 'Remarks', 'type' => 'textarea', 'nullable' => true],
    ];

    protected array $columns = [
        'student.first_name' => 'Student', 'instructor.first_name' => 'Instructor',
        'course.name' => 'Course', 'vehicle.plate_number' => 'Vehicle',
        'lesson_date' => 'Date', 'start_time' => 'Start', 'end_time' => 'End', 'status' => 'Status',
    ];

    protected array $showFields = [
        'student.first_name' => 'Student', 'instructor.first_name' => 'Instructor',
        'course.name' => 'Course', 'vehicle.plate_number' => 'Vehicle',
        'lesson_date' => 'Lesson date', 'start_time' => 'Start time',
        'end_time' => 'End time', 'status' => 'Status', 'remarks' => 'Remarks',
    ];

    protected function rules(?string $id = null): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'instructor_id' => ['required', 'integer', 'exists:instructors,id'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'lesson_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'status' => ['required', Rule::in(['scheduled', 'completed', 'cancelled'])],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function validateRequest(Request $request, ?string $id = null): array
    {
        $validator = Validator::make($request->all(), $this->rules($id), [
            'end_time.after' => 'The lesson end time must be later than the start time.',
            'student_id.exists' => 'Choose a valid student.',
            'instructor_id.exists' => 'Choose a valid instructor.',
            'vehicle_id.exists' => 'Choose a valid vehicle.',
            'course_id.exists' => 'Choose a valid course.',
        ]);

        $validator->after(function (ValidationValidator $validator) use ($request, $id): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $course = Course::find($request->input('course_id'));
            if ($course?->course_type === 'practical' && ! $request->filled('vehicle_id')) {
                $validator->errors()->add('vehicle_id', 'A vehicle is required for practical courses.');
            }

            if ($request->input('status') === 'cancelled') {
                return;
            }

            $start = $request->input('start_time').':00';
            $end = $request->input('end_time').':00';
            $conflicts = [
                'student_id' => 'The student already has a lesson during this time.',
                'instructor_id' => 'The instructor already has a lesson during this time.',
                'vehicle_id' => 'The vehicle is already assigned during this time.',
            ];

            foreach ($conflicts as $field => $message) {
                $value = $request->input($field);
                if (! $value) {
                    continue;
                }

                $query = Lesson::query()
                    ->where($field, $value)
                    ->whereDate('lesson_date', $request->input('lesson_date'))
                    ->where('status', '!=', 'cancelled')
                    ->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);

                if ($id !== null) {
                    $query->whereKeyNot($id);
                }

                if ($query->exists()) {
                    $validator->errors()->add($field, $message);
                }
            }
        });

        return $validator->validate();
    }

    protected function selectOptions(): array
    {
        return [
            'student_id' => Student::query()->orderBy('first_name')->get()
                ->mapWithKeys(fn (Student $student) => [$student->id => $student->first_name.' '.$student->last_name])->all(),
            'instructor_id' => Instructor::query()->orderBy('first_name')->get()
                ->mapWithKeys(fn (Instructor $instructor) => [$instructor->id => $instructor->first_name.' '.$instructor->last_name])->all(),
            'vehicle_id' => Vehicle::query()->orderBy('plate_number')->pluck('plate_number', 'id')->all(),
            'course_id' => Course::query()->orderBy('name')->pluck('name', 'id')->all(),
        ];
    }
}
