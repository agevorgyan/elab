<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed initial Super Admin account using secure env configuration.
     */
    public function run(): void
    {
        $email = mb_strtolower(trim(env('ADMIN_EMAIL', 'admin@elab.am')));
        $password = env('ADMIN_PASSWORD', 'SuperAdmin2026!');

        $passwordHash = password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost'   => 3,
            'threads'     => 4,
        ]);

        User::updateOrCreate(
            ['email' => $email],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Super Admin',
                'password_hash' => $passwordHash,
                'role' => 'SUPER_ADMIN',
                'email_verified_at' => now(),
            ]
        );
    }
}
