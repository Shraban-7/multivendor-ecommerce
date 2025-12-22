<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BkashService
{
    protected string $baseUrl;
    protected string $appKey;
    protected string $appSecret;
    protected string $username;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = config('bkash.base_url');
        $this->appKey = config('bkash.app_key');
        $this->appSecret = config('bkash.app_secret');
        $this->username = config('bkash.username');
        $this->password = config('bkash.password');
    }

    /*-----------------------------------
     | 1. Get Token
     -----------------------------------*/
    public function getToken()
    {
        $response = Http::withHeaders([
            'username' => $this->username,
            'password' => $this->password,
        ])->post($this->baseUrl . '/checkout/token/grant', [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        return $response->json();
    }

    /*-----------------------------------
     | 2. Create Payment
     -----------------------------------*/
    public function createPayment($token, $amount, $invoice)
    {
        return Http::withToken($token)
            ->withHeaders(['X-APP-Key' => $this->appKey])
            ->post($this->baseUrl . '/checkout/payment/create', [
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $invoice,
            ])
            ->json();
    }

    /*-----------------------------------
     | 3. Execute Payment
     -----------------------------------*/
    public function executePayment($token, $paymentID)
    {
        return Http::withToken($token)
            ->withHeaders(['X-APP-Key' => $this->appKey])
            ->post($this->baseUrl . '/checkout/payment/execute/' . $paymentID)
            ->json();
    }

    /*-----------------------------------
     | 4. Query Payment (Optional)
     -----------------------------------*/
    public function queryPayment($token, $paymentID)
    {
        return Http::withToken($token)
            ->withHeaders(['X-APP-Key' => $this->appKey])
            ->get($this->baseUrl . '/checkout/payment/query/' . $paymentID)
            ->json();
    }
}
