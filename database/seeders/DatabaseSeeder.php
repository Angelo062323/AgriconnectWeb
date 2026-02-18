<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lgu;
use App\Models\Farmer;
use App\Models\AssistanceRequest;
use App\Models\Report;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(AdminSeeder::class);

        $lgu = Lgu::create([
            'lgu_name' => 'Sample LGU',
            'municipality' => 'Sample Municipality',
            'province' => 'Sample Province',
            'contact_email' => 'lgu@example.com',
        ]);

        $farmer = Farmer::create([
            'lgu_id' => $lgu->id,
            'rsbsa_number' => 'RSBSA-0001',
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'contact_number' => '09123456789',
            'email' => 'farmer@example.com',
            'crop_type' => 'Rice',
            'farm_location' => 'Sample Farm Location',
            'barangay' => 'Sample Barangay',
            'municipality' => 'Sample Municipality',
            'province' => 'Sample Province',
            'latitude' => null,
            'longitude' => null,
        ]);

        AssistanceRequest::create([
            'farmer_id' => $farmer->id,
            'lgu_id' => $lgu->id,
            'da_id' => null,
            'request_type' => 'plant_seeds',
            'description' => 'Sample request for seed assistance.',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Report::create([
            'farmer_id' => $farmer->id,
            'lgu_id' => $lgu->id,
            'da_id' => null,
            'report_type' => 'Pest infestation',
            'severity' => 'moderate',
            'description' => 'Sample report of pest infestation in crops.',
            'status' => 'pending',
        ]);

    }
}
