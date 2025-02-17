<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            \App\Models\User::factory(10)->create();
        }

        $users = User::inRandomOrder()->take(10)->get();

        foreach ($users as $user) {
            Shop::create([
                'name' => $user->fullname . "'s Shop",
                'image' => 'frontend/images/provider-logo-1.png',
                'total_follower' => rand(100,9999),
                'total_sold' => rand(100,9999),
                'total_item' => rand(100,9999),
                'user_id' => $user->id,
            ]);
        }
    }
}
