<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Medicina General', 'slug' => 'medicina-general', 'description' => 'Atención médica primaria y preventiva.'],
            ['name' => 'Cardiología', 'slug' => 'cardiologia', 'description' => 'Diagnóstico y tratamiento del sistema cardiovascular.'],
            ['name' => 'Dermatología', 'slug' => 'dermatologia', 'description' => 'Tratamiento de enfermedades de la piel.'],
            ['name' => 'Pediatría', 'slug' => 'pediatria', 'description' => 'Atención médica infantil.'],
            ['name' => 'Ginecología', 'slug' => 'ginecologia', 'description' => 'Salud femenina y sistema reproductivo.'],
            ['name' => 'Traumatología', 'slug' => 'traumatologia', 'description' => 'Tratamiento de lesiones del sistema musculoesquelético.'],
            ['name' => 'Oftalmología', 'slug' => 'oftalmologia', 'description' => 'Cuidado de la salud visual y ocular.'],
            ['name' => 'Neurología', 'slug' => 'neurologia', 'description' => 'Diagnóstico y tratamiento de trastornos del sistema nervioso.'],
            ['name' => 'Odontología', 'slug' => 'odontologia', 'description' => 'Salud bucal y dental.'],
            ['name' => 'Psicología', 'slug' => 'psicologia', 'description' => 'Salud mental y bienestar emocional.'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(
                ['slug' => $specialty['slug']],
                $specialty
            );
        }
    }
}
