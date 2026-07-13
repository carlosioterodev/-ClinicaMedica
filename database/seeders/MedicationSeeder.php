<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $medications = [
            ['name' => 'Paracetamol', 'generic_name' => 'Paracetamol', 'presentation' => 'Tabletas 500mg', 'stock' => 500, 'unit_price' => 0.50],
            ['name' => 'Ibuprofeno', 'generic_name' => 'Ibuprofeno', 'presentation' => 'Tabletas 400mg', 'stock' => 300, 'unit_price' => 0.75],
            ['name' => 'Amoxicilina', 'generic_name' => 'Amoxicilina', 'presentation' => 'Cápsulas 500mg', 'stock' => 200, 'unit_price' => 1.20],
            ['name' => 'Omeprazol', 'generic_name' => 'Omeprazol', 'presentation' => 'Cápsulas 20mg', 'stock' => 400, 'unit_price' => 0.80],
            ['name' => 'Loratadina', 'generic_name' => 'Loratadina', 'presentation' => 'Tabletas 10mg', 'stock' => 250, 'unit_price' => 0.60],
            ['name' => 'Metformina', 'generic_name' => 'Metformina', 'presentation' => 'Tabletas 850mg', 'stock' => 350, 'unit_price' => 1.00],
            ['name' => 'Losartán', 'generic_name' => 'Losartán', 'presentation' => 'Tabletas 50mg', 'stock' => 200, 'unit_price' => 1.50],
            ['name' => 'Salbutamol', 'generic_name' => 'Salbutamol', 'presentation' => 'Inhalador 100mcg', 'stock' => 100, 'unit_price' => 8.00],
        ];

        foreach ($medications as $medication) {
            Medication::firstOrCreate(
                ['name' => $medication['name']],
                array_merge($medication, ['is_active' => true])
            );
        }
    }
}
