<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions
        $permissions = [
            'view admin dashboard',
            'manage bookings',
            'manage services',
            'manage staff',
            'manage availability',
            'manage settings', // Includes general settings, holidays, etc.
            'edit own profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Roles and Assign Permissions

        // --- Admin & Owner (Full Access) ---
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(Permission::all());

        $roleOwner = Role::firstOrCreate(['name' => 'owner']);
        $roleOwner->syncPermissions(Permission::all());

        // --- Manager (Bookings, Services, Staff, Availability) ---
        // Manager usually sees the dashboard too
        $roleManager = Role::firstOrCreate(['name' => 'manager']);
        $roleManager->syncPermissions([
            'view admin dashboard',
            'manage bookings',
            'manage services',
            'manage staff',
            'manage availability'
        ]);

        // --- Staff (Bookings, Edit Own Profile) ---
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->syncPermissions([
            'view admin dashboard', // Needed to access the panel
            'manage bookings',
            'edit own profile'
        ]);

        // 3. Assign Admin Role to First User
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
