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
                'name' => 'Test User',
                'email' => 'user@gmail.com',
                'phone' => '01111111111',
                'password' => Hash::make('password'),
                'country_id' => 1,
                'zip' => '1205',
            ],
            [
                'name'        => 'Alice Rahman',
                'email'           => 'alice.rahman@gmail.com',
                'phone'           => '01710000001',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1205',
            ],
            [
                'name'        => 'Kamrul Hasan',
                'email'           => 'kamrul.hasan@gmail.com',
                'phone'           => '01710000002',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1212',
            ],
            [
                'name'        => 'Nusrat Jahan',
                'email'           => 'nusrat.jahan@gmail.com',
                'phone'           => '01710000003',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1000',
            ],
            [
                'name'        => 'Rifat Chowdhury',
                'email'           => 'rifat.chowdhury@gmail.com',
                'phone'           => '01710000004',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1100',
            ],
            [
                'name'        => 'Sadia Karim',
                'email'           => 'sadia.karim@gmail.com',
                'phone'           => '01710000005',
                'password' => Hash::make('password'),
                'country_id'      => 1,
                'zip'             => '1300',
            ],
        ];

        foreach ($users as $user) {
            $user['username'] = str_slug('users', 'username', $user['name']);
            User::create($user);
        }
    }
}
