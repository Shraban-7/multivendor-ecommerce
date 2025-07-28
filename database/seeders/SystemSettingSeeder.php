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
            'logo' => '/logo/slashmart-logo.png',
            'logo_white' => '/logo/slashmart-logo-white.png',
            'favicon' => 'favicon/favicon.ico',
            'apk_version' => '1.0.0',
            'footer_text' => 'We have clothes that suit your style and which you\'re proud to wear. From women to men.'
        ]);
    }
}
