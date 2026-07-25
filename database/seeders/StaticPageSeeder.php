<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'content' => '<p>Welcome to our multi-vendor e-commerce platform in Bangladesh! We aim to connect local sellers with customers across the nation, fostering a vibrant digital marketplace. Our mission is to provide high-quality products and an exceptional shopping experience.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Contact Us',
                'content' => '<p>If you have any questions or need support, please reach out to us:</p><ul><li><strong>Email:</strong> support@yourecommerce.com</li><li><strong>Phone:</strong> +880-XXXXXXXXX</li><li><strong>Address:</strong> [Your Company Address in Dhaka, Bangladesh]</li></ul>',
                'is_active' => true,
            ],
            [
                'title' => 'Help Center',
                'content' => '<p>Find answers to frequently asked questions (FAQs) regarding orders, payments, shipping, and returns. If you cannot find what you are looking for, please use the Contact Us page.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Returns & Refunds',
                'content' => '<p>We offer a flexible return policy. Items can typically be returned within 7 days of delivery, provided they are unused and in their original packaging. Refunds are processed within 3-5 working days after the item is received and inspected.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Terms and Conditions',
                'content' => '<p>By using this platform, you agree to comply with and be bound by the following terms and conditions of use. These terms govern our relationship with you concerning this platform. Please review them carefully.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'content' => '<p>Your privacy is important to us. This policy explains how we collect, use, and protect your personal information when you use our services. We do not sell your data to third parties.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Become a Seller',
                'content' => '<p>Are you a vendor in Bangladesh looking to expand your reach? Join our platform today! We offer competitive commission rates and tools to help you manage your products and sales. Click <a href="#">here</a> to register as a vendor.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Shipping Information',
                'content' => '<p>We use reliable logistics partners for fast delivery across all 64 districts of Bangladesh. Shipping costs and delivery times are calculated at checkout based on your location and the vendor\'s location.</p>',
                'is_active' => true,
            ],
        ];

        $dataToInsert = [];
        foreach ($pages as $page) {
            $dataToInsert[] = [
                'title' => $page['title'],
                'slug' => Str::slug($page['title']),
                'content' => $page['content'],
                'is_active' => $page['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('static_pages')->insertOrIgnore($dataToInsert);
    }
}
