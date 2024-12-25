<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vaccine;

class VaccineSeeder extends Seeder
{
    public function run()
    {
        // Add vaccine data
        Vaccine::create([
            'vaccine_name' => 'Rabies',
            'description' => 'Rabies vaccine for pets.',
        ]);

        Vaccine::create([
            'vaccine_name' => 'Distemper',
            'description' => 'Distemper vaccine for dogs and cats.',
        ]);

        Vaccine::create([
            'vaccine_name' => 'Parvovirus',
            'description' => 'Parvovirus vaccine for dogs.',
        ]);

        Vaccine::create([
            'vaccine_name' => 'Leptospirosis',
            'description' => 'Leptospirosis vaccine for dogs.',
        ]);

        // Add more vaccines as needed
    }
}
