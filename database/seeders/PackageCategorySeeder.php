<?php

namespace Database\Seeders;

use App\Models\PackageCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PackageCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Umroh Reguler',
            'Umroh Plus Turki',
            'Umroh VIP',
            'Haji Khusus',
            'Haji Plus',
        ];

        foreach ($categories as $name) {
            PackageCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => "Paket kategori {$name}.",
                    'status' => true,
                ]
            );
        }
    }
}