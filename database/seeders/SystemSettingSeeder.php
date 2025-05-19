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
            'logo' => 'assets/frontend/images/tesko-logo.png',
            'favicon' => 'assets/frontend/images/favicon.ico',
            'apk_version' => '1.0.0',
        ]);
    }
}
