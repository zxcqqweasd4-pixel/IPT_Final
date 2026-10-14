<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Validation\Rule;

class InstructorController extends ResourceController
{
    protected string $modelClass = Instructor::class;

    protected string $resource = 'instructors';

    protected string $title = 'Instructors';

    protected string $singular = 'instructor';

    protected array $fields = [
        ['name' => 'first_name', 'label' => 'First name', 'type' => 'text'],
        ['name' => 'last_name', 'label' => 'Last name', 'type' => 'text'],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'phone', 'label' => 'Phone', 'type' => 'text'],
        ['name' => 'license_number', 'label' => 'License number', 'type' => 'text'],
        ['name' => 'specialization', 'label' => 'Specialization', 'type' => 'text'],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => [
            'active' => 'Active', 'inactive' => 'Inactive',
        ]],
    ];

    protected array $columns = [
        'first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email',
        'license_number' => 'License number', 'specialization' => 'Specialization', 'status' => 'Status',
    ];

    protected array $showFields = [
        'first_name' => 'First name', 'last_name' => 'Last name', 'email' => 'Email',
        'phone' => 'Phone', 'license_number' => 'License number',
        'specialization' => 'Specialization', 'status' => 'Status',
    ];

    protected array $showWith = ['lessons.student', 'lessons.vehicle', 'lessons.course'];

    protected function rules(?string $id = null): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('instructors', 'email')->ignore($id)],
            'phone' => ['required', 'string', 'max:40'],
            'license_number' => ['required', 'string', 'max:255', Rule::unique('instructors', 'license_number')->ignore($id)],
            'specialization' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
