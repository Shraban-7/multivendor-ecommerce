<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'fullname' => 'Test User',
                'display_name' => 'Test',
                'image' => '/images/user/avatar/user-avatar-1.png',
                'email' => 'user@gmail.com',
                'secondary_email' => 'user@gmail.com',
                'phone' => '01111111111',
                'password' => Hash::make('password'),
                'country_id' => 1,
                'zip' => '1205',
            ],
            [
                'fullname'        => 'Alice Rahman',
                'display_name'    => 'Alice',
                'image'           => '/images/user/avatar/user-avatar-1.png',
                'email'           => 'alice.rahman@gmail.com',
                'secondary_email' => 'alice.rahman.backup@gmail.com',
                'phone'           => '01710000001',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1205',
            ],
            [
                'fullname'        => 'Kamrul Hasan',
                'display_name'    => 'Kamrul',
                'image'           => '/images/user/avatar/user-avatar-2.png',
                'email'           => 'kamrul.hasan@gmail.com',
                'secondary_email' => 'kamrul.hasan.backup@gmail.com',
                'phone'           => '01710000002',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1212',
            ],
            [
                'fullname'        => 'Nusrat Jahan',
                'display_name'    => 'Nusrat',
                'image'           => '/images/user/avatar/user-avatar-3.png',
                'email'           => 'nusrat.jahan@gmail.com',
                'secondary_email' => 'nusrat.jahan.backup@gmail.com',
                'phone'           => '01710000003',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1000',
            ],
            [
                'fullname'        => 'Rifat Chowdhury',
                'display_name'    => 'Rifat',
                'image'           => '/images/user/avatar/user-avatar-4.png',
                'email'           => 'rifat.chowdhury@gmail.com',
                'secondary_email' => 'rifat.chowdhury.backup@gmail.com',
                'phone'           => '01710000004',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1100',
            ],
            [
                'fullname'        => 'Sadia Karim',
                'display_name'    => 'Sadia',
                'image'           => '/images/user/avatar/user-avatar-5.png',
                'email'           => 'sadia.karim@gmail.com',
                'secondary_email' => 'sadia.karim.backup@gmail.com',
                'phone'           => '01710000005',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1300',
            ],
        ];

        foreach ($users as $user) {
            $user['username'] = str_slug('users', 'username', $user['fullname']);
            User::create($user);
        }
    }
}
