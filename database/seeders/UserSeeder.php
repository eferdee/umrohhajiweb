<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $customerRole = Role::where('name', 'customer')->first();

        User::updateOrCreate(
            ['email' => 'admin@alamintravel.co.id'],
            [
                'role_id' => $adminRole?->id,
                'name' => 'Admin Zafa Tour Travel',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'address' => 'Kantor Pusat Zafa Tour Travel',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'jamaah@example.com'],
            [
                'role_id' => $customerRole?->id,
                'name' => 'Jamaah Contoh',
                'password' => Hash::make('password123'),
                'phone' => '081298765432',
                'address' => 'Jl. Contoh Alamat No. 45',
                'email_verified_at' => now(),
            ]
        );
    }
}