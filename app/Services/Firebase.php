<?php

namespace App\Services;

use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class Firebase
{
    private $messaging;

    // public function notifyCustomer($deviceToken, $title, $body, $data = [])
    // {
    //     return $this->send($deviceToken, $title, $body, $data, storage_path("app/firebase/customer.json"));
    // }

    // public function notifySeller($deviceToken, $title, $body, $data = [])
    // {
    //     return $this->send($deviceToken, $title, $body, $data, storage_path("app/firebase/seller.json"));
    // }

    public function notifyPaymentListener($deviceToken, $title, $body, $data = [])
    {
        return $this->send($deviceToken, $title, $body, $data, storage_path('app/firebase/payment-listener.json'));
    }

    private function send($deviceToken, $title, $body, $data, $credentialPath)
    {
        try {
            $firebase = (new Factory)
                ->withServiceAccount($credentialPath)
                ->createMessaging();

            $message = CloudMessage::new()
                ->withNotification([
                    'title' => $title,
                    'body' => $body,
                ])
                ->withData($data)
                ->toToken($deviceToken);

            return $firebase->send($message);
        } catch (Exception $e) {
            \Log::error('FCM send error: '.$e->getMessage());

            return $e->getMessage();
        }
    }

    public function sendNotificationMultiple($deviceTokens, $title, $body, $data = [])
    {
        $chunks = array_chunk($deviceTokens, 500);
        $responses = [];

        foreach ($chunks as $chunk) {
            $message = CloudMessage::new()
                ->withNotification([
                    'title' => $title,
                    'body' => $body,
                ]);

            try {
                $response = $this->messaging->sendMulticast($message, $chunk);
                $responses[] = $response;
            } catch (Exception $e) {
            }
        }

        return $response ?? null;
    }
}
