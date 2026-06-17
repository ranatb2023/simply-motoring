<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeManagerUser extends Command
{
    protected $signature = 'user:make-manager {email} {password}';

    protected $description = 'Create or update a user with the "manager" role (bookings, services, staff, availability)';

    public function handle(): int
    {
        $email    = $this->argument('email');
        $password = $this->argument('password');

        $user = User::firstOrNew(['email' => $email]);
        if (!$user->exists) {
            $user->name = ucfirst(strtok($email, '@'));
        }
        $user->password          = Hash::make($password);
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->save();

        $user->syncRoles(['manager']);

        $this->info("Manager user ready: {$email} (role: manager)");
        return self::SUCCESS;
    }
}
