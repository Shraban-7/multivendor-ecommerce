<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::create([
            'app_name'=> 'Slash Mart',
            'logo' => '/logo/tesko-logo.png',
            'logo_white' => '/logo/tesko-login-logo.png',
            'favicon' => 'favicon/favicon.ico',
            'apk_version' => '1.0.0',
        ]);
    }
}
