<?php

namespace App\Domain\Payment\Database\Seeders;

use App\Domain\Payment\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Aamarpay',
                'slug' => 'aamarpay',
                'image' => 'images/payment-gateways/aamarpay.png',
                'payment_url' => env('AAMARPAY_PAYMENT_URL'),
                'credentials' => [
                    'store_id' => env('AAMARPAY_STORE_ID'),
                    'signature_key' => env('AAMARPAY_SIGNATURE_KEY'),
                    'sandbox_mode' => true,
                ],
                'is_enabled' => true,
                'is_default' => true,
            ],
            [
                'name' => 'SSLCommerz',
                'slug' => 'sslcommerz',
                'payment_url' => 'https://secure.aamarpay.com',
                'image' => 'images/payment-gateways/sslcommerz.png',
                'credentials' => [
                    'store_id' => 'ssl_store_id',
                    'store_password' => 'ssl_store_password',
                    'sandbox_mode' => true,
                ],
                'is_enabled' => true,
                'is_default' => false,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(['slug' => $gateway['slug']], $gateway);
        }
    }
}
