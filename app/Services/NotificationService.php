<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send($userId, $title, $message, $targetType = null, $targetID = null, $sendPush = false)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'send_push' => $sendPush,
            'target_type' => $targetType,
            'target_id' => $targetID,
        ]);

        if ($sendPush) {
            self::sendPushNotification($notification);
        }
    }

    protected static function sendPushNotification(Notification $notification) {}
}
