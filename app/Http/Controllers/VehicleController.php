<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Validation\Rule;

class VehicleController extends ResourceController
{
    protected string $modelClass = Vehicle::class;

    protected string $resource = 'vehicles';

    protected string $title = 'Vehicles';

    protected string $singular = 'vehicle';

    protected array $fields = [
        ['name' => 'plate_number', 'label' => 'Plate number', 'type' => 'text'],
        ['name' => 'model', 'label' => 'Model', 'type' => 'text'],
        ['name' => 'type', 'label' => 'Vehicle type', 'type' => 'select', 'options' => [
            'sedan' => 'Sedan', 'SUV' => 'SUV', 'motorcycle' => 'Motorcycle', 'truck' => 'Truck',
        ]],
        ['name' => 'transmission', 'label' => 'Transmission', 'type' => 'select', 'options' => [
            'manual' => 'Manual', 'automatic' => 'Automatic',
        ]],
        ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => [
            'available' => 'Available', 'maintenance' => 'Maintenance',
        ]],
    ];

    protected array $columns = [
        'plate_number' => 'Plate number', 'model' => 'Model', 'type' => 'Type',
        'transmission' => 'Transmission', 'status' => 'Status',
    ];

    protected array $showFields = [
        'plate_number' => 'Plate number', 'model' => 'Model', 'type' => 'Type',
        'transmission' => 'Transmission', 'status' => 'Status',
    ];

    protected array $showWith = ['lessons.student', 'lessons.instructor', 'lessons.course'];

    protected function rules(?string $id = null): array
    {
        return [
            'plate_number' => ['required', 'string', 'max:255', Rule::unique('vehicles', 'plate_number')->ignore($id)],
            'model' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['sedan', 'SUV', 'motorcycle', 'truck'])],
            'transmission' => ['required', Rule::in(['manual', 'automatic'])],
            'status' => ['required', Rule::in(['available', 'maintenance'])],
        ];
    }
}
