<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiUrl;

    protected $apiKey;

    protected $secretKey;

    protected $callerId;

    protected $bypassUrl;

    public function __construct()
    {
        $this->apiUrl = config('sms.api_url');
        $this->apiKey = config('sms.api_key');
        $this->secretKey = config('sms.secret_key');
        $this->callerId = config('sms.caller_id');
        $this->bypassUrl = config('sms.bypass_url');
    }

    public function send($message, $recipients)
    {
        $numbers = explode(',', $recipients);
        $formattedNumbers = array_map(function ($number) {
            return format_bd_phone($number);
        }, $numbers);
        $formattedRecipients = implode(',', $formattedNumbers);

        $apiUrl = $this->apiUrl.'/sendtext';

        try {
            $response = Http::post($this->bypassUrl, [
                'apiUrl' => $apiUrl,
                'apikey' => $this->apiKey,
                'secretkey' => $this->secretKey,
                'callerID' => $this->callerId,
                'messageContent' => $message,
                'toUser' => $formattedRecipients,
            ]);

            SmsLog::create([
                'message' => $message,
                'recipients' => $formattedRecipients,
                'status' => $response->successful() ? 'success' : 'failed',
                'response' => json_encode($response->json()),
            ]);

            return $response->json();
        } catch (\Throwable $e) {
            SmsLog::create([
                'message' => $message,
                'recipients' => $formattedRecipients,
                'status' => 'error',
                'response' => $e->getMessage(),
            ]);

            return [
                'Text' => $e->getMessage(),
            ];
        }
    }
}
