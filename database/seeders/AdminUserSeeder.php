<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        User::updateOrCreate(
            ['email' => 'sevren.alasmar@gmail.com'],
            [
                'name' => 'Admin',
                'surname' => 'User',
                'password' => Hash::make('AdminPassword123!'),
                'role' => 'admin',
                'balance' => 10000,
                'currency' => 'USD',
                'is_verified' => 1,
                'status' => 'active',
                'phone' => '+1234567890',
                'age' => 30,
            ]
        );
    }
}

