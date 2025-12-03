<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

            $this->call([
        AdminUserSeeder::class,
        TransferServiceSeeder::class,
        ExchangeRateSeeder::class,
         ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_verified' => true,
            'role' => 'user',
        ]);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_verified' => true,
            'is_admin' => true,
            'role' => 'admin',
        ]);

        // Run commission seeder
        $this->call(TestAgentCommissionSeeder::class);
    }
}
