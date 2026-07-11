<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seeds one super_admin for local testing. See LARAVEL-DYNAMIZATION-PLAN.md
     * Phase 2.4. Password is read from an env var so it's never hardcoded or
     * committed — set ADMIN_SEED_PASSWORD before running this seeder.
     */
    public function run(): void
    {
        $password = env('ADMIN_SEED_PASSWORD');

        if (! $password) {
            $this->command->error('Set ADMIN_SEED_PASSWORD in .env before running this seeder.');

            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@bcc.gov.bd'],
            [
                'name' => 'Sk. Mofizur R.',
                'password' => Hash::make($password),
                'is_admin' => true,
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );

        $this->command->info('Seeded super_admin: admin@bcc.gov.bd');
    }
}
