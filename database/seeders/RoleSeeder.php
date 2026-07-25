<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator dengan akses penuh ke seluruh fitur dashboard.',
            ],
            [
                'name' => 'customer',
                'description' => 'Jamaah/pengguna umum yang mendaftar paket Umroh atau Haji.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}