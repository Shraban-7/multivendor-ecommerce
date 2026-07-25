<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ],
            [
                'name' => 'Alice Rahman',
                'email' => 'alice.rahman@gmail.com',
                'phone' => '01710000001',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Kamrul Hasan',
                'email' => 'kamrul.hasan@gmail.com',
                'phone' => '01710000002',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat.jahan@gmail.com',
                'phone' => '01710000003',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Rifat Chowdhury',
                'email' => 'rifat.chowdhury@gmail.com',
                'phone' => '01710000004',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sadia Karim',
                'email' => 'sadia.karim@gmail.com',
                'phone' => '01710000005',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $user) {
            $user['username'] = str_slug('users', 'username', $user['name']);
            User::create($user);
        }
    }
}
