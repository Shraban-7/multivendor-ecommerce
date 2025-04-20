<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Visa',
                'image' => 'images/payment_gateways/payment-gateway-1.png',
                'link' => 'https://bd.visa.com/pay-with-visa/find-a-card/credit-cards.html',
            ],
            [
                'name' => 'MasterCard',
                'image' => 'images/payment_gateways/payment-gateway-2.png',
                'link' => 'https://www.mastercard.com.bd',
            ],
            [
                'name' => 'PayPal',
                'image' => 'images/payment_gateways/payment-gateway-3.png',
                'link' => 'https://www.paypal.com/',
            ],
            [
                'name' => 'iPay',
                'image' => 'images/payment_gateways/payment-gateway-4.png',
                'link' => 'https://ipay.com.bd/',
            ],
            [
                'name' => 'G Pay',
                'image' => 'images/payment_gateways/payment-gateway-5.png',
                'link' => 'https://pay.google.com/',
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::create([
                'name' => $gateway['name'],
                'image' => $gateway['image'],
                'link' => $gateway['link'],
                'status' => true,
            ]);
        }
    }
}
