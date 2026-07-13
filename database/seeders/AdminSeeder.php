<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@clinica.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        $admin->assignRole('admin');

        Profile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'dni' => 'ADMIN001',
                'phone' => '5555550001',
                'gender' => 'M',
            ]
        );

        // Doctor de ejemplo
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@clinica.com'],
            [
                'name' => 'Dr. Juan Pérez',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        $doctor->assignRole('doctor');

        Profile::firstOrCreate(
            ['user_id' => $doctor->id],
            [
                'dni' => 'DOC001',
                'phone' => '5555550002',
                'gender' => 'M',
            ]
        );

        // Nurse de ejemplo
        $nurse = User::firstOrCreate(
            ['email' => 'nurse@clinica.com'],
            [
                'name' => 'Enfermera Ana López',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        $nurse->assignRole('nurse');

        Profile::firstOrCreate(
            ['user_id' => $nurse->id],
            [
                'dni' => 'NUR001',
                'phone' => '5555550003',
                'gender' => 'F',
            ]
        );

        // Patient de ejemplo
        $patient = User::firstOrCreate(
            ['email' => 'paciente@clinica.com'],
            [
                'name' => 'María García',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        $patient->assignRole('patient');

        Profile::firstOrCreate(
            ['user_id' => $patient->id],
            [
                'dni' => 'PAC001',
                'phone' => '5555550004',
                'date_of_birth' => '1990-05-15',
                'gender' => 'F',
                'blood_type' => 'O+',
                'address' => 'Calle Ejemplo 123',
            ]
        );
    }
}
