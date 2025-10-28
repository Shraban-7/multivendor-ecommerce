<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'duration_type' => 'monthly',
                'product_limit' => 10,
                'commission_rate' => 10.00,
                'pos_access' => false,
                'analytics_access' => false,
                'priority_support' => false,
                'custom_domain' => false,
                'staff_account_limit' => 0,
            ],
            [
                'name' => 'Silver',
                'price' => 999,
                'duration_type' => 'monthly',
                'product_limit' => 200,
                'commission_rate' => 8.00,
                'pos_access' => true,
                'analytics_access' => true,
                'priority_support' => true,
                'custom_domain' => false,
                'staff_account_limit' => 1,
            ],
            [
                'name' => 'Gold',
                'price' => 2499,
                'duration_type' => 'monthly',
                'product_limit' => 1000,
                'commission_rate' => 7.00,
                'pos_access' => true,
                'analytics_access' => true,
                'priority_support' => true,
                'custom_domain' => true,
                'staff_account_limit' => 3,
            ],
            [
                'name' => 'Platinum',
                'price' => 4999,
                'duration_type' => 'monthly',
                'product_limit' => 0, // 0 = unlimited
                'commission_rate' => 5.00,
                'pos_access' => true,
                'analytics_access' => true,
                'priority_support' => true,
                'custom_domain' => true,
                'staff_account_limit' => 10,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
