<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('system_settings')->truncate();
        SystemSetting::create([
            'app_name'=> 'Slash Mart',
            'logo' => '/logo/slashmart-logo.png',
            'logo_white' => '/logo/slashmart-logo-white.png',
            'favicon' => 'favicon/favicon.ico',
            'apk_version' => '1.0.0',
            'footer_text' => 'We have clothes that suit your style and which you\'re proud to wear. From women to men.',
            'email' => 'support@slashmart.com',
            'phone' => '+880 1700-000000',
            'address' => 'Level 4, Gulshan-1, Dhaka-1212, Bangladesh'
        ]);
    }
}
