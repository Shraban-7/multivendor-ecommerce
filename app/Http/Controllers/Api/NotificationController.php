<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest('id')->paginate(25);

        return apiResourceResponse(NotificationResource::collection($notifications));
    }

    public function notificationCount()
    {
        return apiResponse([
            'count' => Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count()
        ], "Unread notification count");
    }
}
