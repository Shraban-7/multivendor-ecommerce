<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BkashService
{
    protected $baseUrl;

    protected $appKey;

    protected $appSecret;

    protected $username;

    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('bkash.base_url');
        $this->appKey = config('bkash.app_key');
        $this->appSecret = config('bkash.app_secret');
        $this->username = config('bkash.username');
        $this->password = config('bkash.password');
    }

    /**
     * 1. Grant Token: Generates an access token required for other API calls.
     */
    public function grantToken()
    {
        $url = $this->baseUrl.'/tokenized/checkout/token/grant';

        $headers = [
            'Content-Type' => 'application/json',
            'username' => $this->username,
            'password' => $this->password,
        ];

        $body = [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        if ($response->successful()) {
            $data = $response->json();
            // Store token in session (expires in 3600 seconds typically)
            Session::put('bkash_token', $data['id_token']);

            return $data['id_token'];
        }

        Log::error('Bkash Grant Token Failed', ['response' => $response->body()]);

        return null;
    }

    /**
     * 2. Create Payment: Initializes the payment and returns the checkout URL.
     */
    public function createPayment($amount, $invoiceNumber)
    {
        $token = Session::get('bkash_token');

        // If token doesn't exist or is expired, generate a new one
        if (! $token) {
            $token = $this->grantToken();
        }

        if (! $token) {
            throw new \Exception('Failed to generate bKash access token.');
        }

        $url = $this->baseUrl.'/tokenized/checkout/create';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => $token,
            'X-APP-Key' => $this->appKey,
        ];

        $body = [
            'mode' => '0011',
            'payerReference' => $invoiceNumber,
            'callbackURL' => config('bkash.callback_url'),
            'amount' => $amount,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $invoiceNumber,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        return $response->json();
    }

    /**
     * 3. Execute Payment: Finalizes the payment after user enters PIN.
     */
    public function executePayment($paymentID)
    {
        $token = Session::get('bkash_token');

        if (! $token) {
            $token = $this->grantToken();
        }

        $url = $this->baseUrl.'/tokenized/checkout/execute';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => $token,
            'X-APP-Key' => $this->appKey,
        ];

        $body = [
            'paymentID' => $paymentID,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        return $response->json();
    }

    /**
     * 4. Query Payment: Check status of a payment (Optional but recommended)
     */
    public function queryPayment($paymentID)
    {
        $token = Session::get('bkash_token');

        $url = $this->baseUrl.'/tokenized/checkout/payment/status';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => $token,
            'X-APP-Key' => $this->appKey,
        ];

        $body = [
            'paymentID' => $paymentID,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        return $response->json();
    }
}
