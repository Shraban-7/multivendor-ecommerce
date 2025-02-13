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
                'fullname' => 'Dummy User',
                'email' => 'user@example.com',
            ],
        ];

        foreach ($users as $user) {

            $user['username'] = str_slug('users','username',$user['fullname']);
            $user['password'] = Hash::make('password');

            User::create($user);
        }

    }
}
