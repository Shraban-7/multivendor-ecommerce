<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AamarpayService
{
    protected $storeId;

    protected $signatureKey;

    protected $sandbox;

    public function __construct()
    {
        $this->storeId = config('services.aamarpay.store_id');
        $this->signatureKey = config('services.aamarpay.signature_key');
        $this->sandbox = config('services.aamarpay.sandbox', true);
    }

    protected function getApiUrl()
    {
        return $this->sandbox
            ? 'https://sandbox.aamarpay.com/jsonpost.php'
            : 'https://secure.aamarpay.com/jsonpost.php';
    }

    public function initiate(array $paymentData)
    {
        $fields = array_merge([
            'store_id' => $this->storeId,
            'signature_key' => $this->signatureKey,
            'currency' => 'BDT',
            'type' => 'json',
        ], $paymentData);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->getApiUrl(), $fields);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('AamarPay API Error: '.$response->body());
    }
}
