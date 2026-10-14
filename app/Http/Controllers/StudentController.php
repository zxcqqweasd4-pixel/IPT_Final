<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Validation\Rule;

class StudentController extends ResourceController
{
    protected string $modelClass = Student::class;

    protected string $resource = 'students';

    protected string $title = 'Students';

    protected string $singular = 'student';

    protected array $fields = [
        ['name' => 'first_name', 'label' => 'First name', 'type' => 'text'],
        ['name' => 'last_name', 'label' => 'Last name', 'type' => 'text'],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
        ['name' => 'address', 'label' => 'Address', 'type' => 'textarea'],
        ['name' => 'birthdate', 'label' => 'Birthdate', 'type' => 'date'],
        ['name' => 'license_status', 'label' => 'License status', 'type' => 'select', 'options' => [
            'none' => 'None', 'student_permit' => 'Student permit', 'non_pro' => 'Non-pro', 'pro' => 'Pro',
        ]],
    ];

    protected array $columns = [
        'first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email',
        'phone' => 'Phone', 'license_status' => 'License status',
    ];

    protected array $showFields = [
        'first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email',
        'phone' => 'Phone', 'address' => 'Address', 'birthdate' => 'Birthdate',
        'license_status' => 'License status',
    ];

    protected array $showWith = ['lessons.instructor', 'lessons.vehicle', 'lessons.course'];

    protected function rules(?string $id = null): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($id)],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:2000'],
            'birthdate' => ['required', 'date', 'before:today'],
            'license_status' => ['required', Rule::in(['none', 'student_permit', 'non_pro', 'pro'])],
        ];
    }
}
