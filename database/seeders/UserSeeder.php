<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'fullname' => 'Client User',
                'display_name' => 'Client',
                'image' => 'frontend/images/user-avatar-1.png',
                'email' => 'client@example.com',
                'secondary_email' => 'client_secondary@example.com',
                'phone' => '12345678',
                'password' => 'password',
                'country_id' => 1,
                'zip' => '1200'
            ],
        ];

        foreach($users as $user)
        {
            $user['username'] = str_slug('users','username',$user['fullname']);
            User::create($user);
        }
    }
}
