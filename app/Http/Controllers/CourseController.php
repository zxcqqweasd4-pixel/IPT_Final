<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Validation\Rule;

class CourseController extends ResourceController
{
    protected string $modelClass = Course::class;

    protected string $resource = 'courses';

    protected string $title = 'Courses';

    protected string $singular = 'course';

    protected array $fields = [
        ['name' => 'name', 'label' => 'Course name', 'type' => 'text'],
        ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
        ['name' => 'course_type', 'label' => 'Course type', 'type' => 'select', 'options' => [
            'theory' => 'Theory', 'practical' => 'Practical',
        ]],
        ['name' => 'duration_hours', 'label' => 'Duration (hours)', 'type' => 'number'],
        ['name' => 'fee', 'label' => 'Fee', 'type' => 'number'],
    ];

    protected array $columns = [
        'name' => 'Course', 'course_type' => 'Type', 'duration_hours' => 'Hours', 'fee' => 'Fee',
    ];

    protected array $showFields = [
        'name' => 'Course name', 'description' => 'Description', 'course_type' => 'Course type',
        'duration_hours' => 'Duration (hours)', 'fee' => 'Fee',
    ];

    protected array $showWith = ['lessons.student', 'lessons.instructor', 'lessons.vehicle'];

    protected function rules(?string $id = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:3000'],
            'course_type' => ['required', Rule::in(['theory', 'practical'])],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:1000'],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }
}
