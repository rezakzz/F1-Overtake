<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@f1store.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin@f1store.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Staff
        User::updateOrCreate(
            ['email' => 'staff@f1store.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // Viewer
        User::updateOrCreate(
            ['email' => 'viewer@f1store.com'],
            [
                'name' => 'Viewer',
                'password' => Hash::make('password'),
                'role' => 'viewer',
            ]
        );

        // Customer
        User::updateOrCreate(
            ['email' => 'customer@f1store.com'],
            [
                'name' => 'Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
    }
}
