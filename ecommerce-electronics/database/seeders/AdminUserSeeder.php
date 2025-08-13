<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@electronics.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'address_line_1' => '123 Admin Street',
            'city' => 'Admin City',
            'state' => 'Admin State',
            'postal_code' => '12345',
            'country' => 'United States',
            'is_active' => true,
        ]);

        // Create a sample customer
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
            'phone' => '+0987654321',
            'address_line_1' => '456 Customer Ave',
            'city' => 'Customer City',
            'state' => 'Customer State',
            'postal_code' => '54321',
            'country' => 'United States',
            'is_active' => true,
        ]);
    }
}
