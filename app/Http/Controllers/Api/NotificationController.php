<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())->latest('id')->paginate(15);

        $unreadIds = $notifications->where('is_read', false)->pluck('id')->toArray();

        $request->attributes->set('notifications_to_mark_read', $unreadIds);

        return apiResourceResponse(NotificationResource::collection($notifications));
    }

    public function notificationCount()
    {
        return apiResponse([
            'count' => Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count(),
        ], 'Unread notification count');
    }
}
