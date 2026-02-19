<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Author Role
        $roleAuthor = Role::firstOrCreate(['name' => 'author']);

        // Ensure 'view admin dashboard' permission exists (assuming it was created in RoleSeeder)
        // If not, create it just in case
        Permission::firstOrCreate(['name' => 'view admin dashboard']);

        // Assign basic permissions to author
        $roleAuthor->syncPermissions([
            'view admin dashboard',
            'edit own profile'
            // Add other permissions if needed, e.g., 'manage blog'
        ]);

        // 2. Create Dummy Authors
        $authors = [
            [
                'name' => 'John Doe (Author)',
                'email' => 'john.author@example.com',
            ],
            [
                'name' => 'Jane Smith (Author)',
                'email' => 'jane.author@example.com',
            ],
            [
                'name' => 'Robert Writer',
                'email' => 'robert.writer@example.com',
            ],
        ];

        foreach ($authors as $authorData) {
            $user = User::firstOrCreate(
                ['email' => $authorData['email']],
                [
                    'name' => $authorData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            if (!$user->hasRole('author')) {
                $user->assignRole('author');
            }
        }

        $this->command->info('Author role and dummy authors seeding completed.');
    }
}
