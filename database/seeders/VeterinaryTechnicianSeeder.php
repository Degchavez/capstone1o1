<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VeterinaryTechnician;

class VeterinaryTechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert sample veterinary technicians
        VeterinaryTechnician::create([
            'full_name' => 'John Doe',
            'contact_number' => '123-456-7890',
            'email' => 'johndoe@example.com',
        ]);

        VeterinaryTechnician::create([
            'full_name' => 'Jane Smith',
            'contact_number' => '987-654-3210',
            'email' => 'janesmith@example.com',
        ]);

        VeterinaryTechnician::create([
            'full_name' => 'Alice Johnson',
            'contact_number' => '555-555-5555',
            'email' => 'alicejohnson@example.com',
        ]);
    }
}
