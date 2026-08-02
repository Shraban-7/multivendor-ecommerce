<?php

namespace Database\Seeders;

use App\Domain\Support\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('system_settings')->truncate();
        SystemSetting::create([
            'app_name' => 'Shob Cart',
            'logo' => '/logo/logo.png',
            'logo_white' => '/logo/logo-white.png',
            'favicon' => 'favicon/favicon.ico',
            'apk_version' => '1.0.0',
            'footer_text' => '© '.date('Y').' Shob Cart. All rights reserved.',
            'email' => 'support@shopcart.com',
            'phone' => '+880 1600-000000',
            'address' => 'Level 4, Gulshan-2, Dhaka-1212, Bangladesh',
        ]);
    }
}
