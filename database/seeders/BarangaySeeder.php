<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangaySeeder extends Seeder
{
    public function run()
    {
        $barangays = [
            ['barangay_name' => 'Bagontaas'],
            ['barangay_name' => 'Batangan'],
            ['barangay_name' => 'Colonia'],
            ['barangay_name' => 'Concepcion'],
            ['barangay_name' => 'Dagat Kidavao'],
            ['barangay_name' => 'Kahaponan'],
            ['barangay_name' => 'Lourdes'],
            ['barangay_name' => 'Lumbo'],
            ['barangay_name' => 'Maapag'],
            ['barangay_name' => 'Mailag'],
            ['barangay_name' => 'Malingon'],
            ['barangay_name' => 'Mt. Nebo'],
            ['barangay_name' => 'Pangantucan'],
            ['barangay_name' => 'Poblacion'],
            ['barangay_name' => 'San Carlos'],
            ['barangay_name' => 'Sinayawan'],
            ['barangay_name' => 'Tongantongan'],
        ];

        DB::table('barangay')->insert($barangays);  // Use singular "barangay" here
    }
}
