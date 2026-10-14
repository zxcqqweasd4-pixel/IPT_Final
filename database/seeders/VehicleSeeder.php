<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['plate_number' => 'ABC-1234', 'model' => 'Toyota Vios', 'type' => 'sedan', 'transmission' => 'automatic', 'status' => 'available'],
            ['plate_number' => 'DEF-2345', 'model' => 'Honda City', 'type' => 'sedan', 'transmission' => 'manual', 'status' => 'available'],
            ['plate_number' => 'GHI-3456', 'model' => 'Toyota Innova', 'type' => 'SUV', 'transmission' => 'automatic', 'status' => 'available'],
            ['plate_number' => 'JKL-4567', 'model' => 'Honda Click 160', 'type' => 'motorcycle', 'transmission' => 'automatic', 'status' => 'available'],
            ['plate_number' => 'MNO-5678', 'model' => 'Yamaha MT-15', 'type' => 'motorcycle', 'transmission' => 'manual', 'status' => 'available'],
            ['plate_number' => 'PQR-6789', 'model' => 'Mitsubishi L300', 'type' => 'truck', 'transmission' => 'manual', 'status' => 'maintenance'],
        ] as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
