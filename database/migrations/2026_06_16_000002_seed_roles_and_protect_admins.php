<?php

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Ensure all roles & permissions exist (idempotent)
        (new RoleSeeder())->run();

        // Protect existing admins: any user who currently has NO role becomes an
        // admin, so the new permission-gated sidebar doesn't hide tabs from them.
        User::query()->get()->each(function (User $user) {
            if ($user->roles()->count() === 0) {
                $user->assignRole('admin');
            }
        });
    }

    public function down(): void
    {
        // No-op: we don't want to strip roles on rollback.
    }
};
