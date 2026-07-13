<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the default admin account.
     *
     * Default credentials (CHANGE IN PRODUCTION):
     *   email: admin@store.test
     *   password: password
     */
    public function run(): void
    {
        if (User::query()->where('email', 'admin@store.test')->exists()) {
            return;
        }

        User::factory()->admin()->create([
            'name' => 'مدير المتجر',
            'email' => 'admin@store.test',
        ]);
    }
}
