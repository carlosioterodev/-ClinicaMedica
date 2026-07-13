<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'doctor', 'nurse', 'patient'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $permissions = [
            'users.manage', 'users.view',
            'appointments.create', 'appointments.view', 'appointments.cancel', 'appointments.update-status',
            'medical-records.create', 'medical-records.view', 'medical-records.update',
            'prescriptions.create', 'prescriptions.view',
            'invoices.create', 'invoices.view', 'invoices.update', 'invoices.pay',
            'specialties.manage', 'rooms.manage',
            'triage.manage',
            'reports.view',
            'schedule.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        Role::findByName('admin')->givePermissionTo($permissions);

        Role::findByName('doctor')->givePermissionTo([
            'appointments.view', 'appointments.update-status',
            'medical-records.create', 'medical-records.view', 'medical-records.update',
            'prescriptions.create', 'prescriptions.view',
            'schedule.manage',
        ]);

        Role::findByName('nurse')->givePermissionTo([
            'appointments.view', 'triage.manage',
        ]);

        Role::findByName('patient')->givePermissionTo([
            'appointments.create', 'appointments.view', 'appointments.cancel',
            'medical-records.view', 'prescriptions.view', 'invoices.view',
        ]);
    }
}
