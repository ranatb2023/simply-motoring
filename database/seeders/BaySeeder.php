<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bay;

class BaySeeder extends Seeder
{
    public function run(): void
    {
        // 2 MOT Bays
        Bay::create(['name' => 'MOT Bay 1', 'type' => 'mot']);
        Bay::create(['name' => 'MOT Bay 2', 'type' => 'mot']);

        // 3 Service Bays
        Bay::create(['name' => 'Service Bay 1', 'type' => 'service']);
        Bay::create(['name' => 'Service Bay 2', 'type' => 'service']);
        Bay::create(['name' => 'Service Bay 3', 'type' => 'service']);

        // 1 Repair Bay
        Bay::create(['name' => 'Repair Bay 1', 'type' => 'repair']);
    }
}
