<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'MOT', 'type' => 'mot', 'duration_minutes' => 60, 'price' => 45.00],
            ['name' => 'Full Service', 'type' => 'service', 'duration_minutes' => 180, 'price' => 150.00],
            ['name' => 'Interim Service', 'type' => 'service', 'duration_minutes' => 90, 'price' => 80.00],
            ['name' => 'Diagnostic / Repair', 'type' => 'repair', 'duration_minutes' => 60, 'price' => 60.00]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
