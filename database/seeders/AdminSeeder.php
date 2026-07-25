<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (AdminRole::cases() as $roleName) {
            $role = Role::create([
                'name' => $roleName->value,
                'title' => $roleName->title(),
            ]);

            $adminName = $roleName->title();
            $userName = strtolower(str_replace(' ', '_', $adminName));

            Admin::create([
                'name' => $adminName,
                'username' => $userName,
                'email' => $userName.'@gmail.com',
                'password' => Hash::make('password'),
                'role_id' => $role->id,
            ]);
        }

    }
}
