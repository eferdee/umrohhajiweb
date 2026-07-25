<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Al-Amin Travel', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Wujudkan Perjalanan Ibadah yang Nyaman & Berkah', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '081234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'info@alamintravel.co.id', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Jl. Contoh Alamat No. 123', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'operational_hours', 'value' => 'Senin - Sabtu, 08.00 - 17.00 WIB', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'bank_account', 'value' => 'Bank Contoh - 1234567890 a.n. Al-Amin Travel', 'type' => 'text', 'group' => 'payment'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}