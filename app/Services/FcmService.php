<?php

namespace App\Services;

use Exception;

class FcmService
{
    const PAYMENT_LISTENER = 'payment-listener';

    public function notifyPaymentListener($deviceToken, $title, $body, $data = [])
    {
        return $this->notify($this::PAYMENT_LISTENER, $deviceToken, $title, $body, $data);
    }

    public function notify($appType, $deviceToken, $title, $body, $data = [])
    {
        $projectId = $this->getProjectId($appType);
        $accessToken = $this->getAccessToken($appType);

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
            ],
        ];

        if (!empty($data)) {
            $stringData = array_map(fn($v) => (string) $v, $data); //all values must be string
            $payload['message']['data'] = $stringData;
        }

        $headers = [
            "Authorization: Bearer {$accessToken}",
            "Content-Type: application/json",
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            \Log::error("FCM send error ($appType): " . $error);
            return null;
        }

        return $response;
    }

    /**
     * Generate OAuth2 access token using service account.
     */
    private function getAccessToken($appType)
    {
        $keyFile = $this->getKeyFile($appType);
        $jwt = $this->createJwt($keyFile);

        // Exchange JWT for access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://oauth2.googleapis.com/token");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]));

        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response['access_token'] ?? null;
    }

    /**
     * Create JWT manually for Google OAuth2.
     */
    private function createJwt($keyFile)
    {
        $now = time();
        $header = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claimSet = base64_encode(json_encode([
            'iss' => $keyFile['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $signature = '';
        openssl_sign("$header.$claimSet", $signature, $keyFile['private_key'], 'SHA256');
        $signature = base64_encode($signature);

        return "$header.$claimSet.$signature";
    }

    /**
     * Get Firebase service account file.
     */
    private function getKeyFile($appType)
    {
        $pathMap = [
            'payment-listener' => storage_path('app/firebase/payment-listener.json'),
        ];

        return json_decode(file_get_contents($pathMap[$appType]), true);
    }

    /**
     * Get Firebase project ID.
     */
    private function getProjectId($appType)
    {
        $keyFile = $this->getKeyFile($appType);
        return $keyFile['project_id'];
    }
}
