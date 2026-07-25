<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Support\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())->latest('id')->paginate(20);

        $unreadIds = $notifications->where('is_read', false)->pluck('id')->toArray();

        $request->attributes->set('notifications_to_mark_read', $unreadIds);

        return view('frontend.pages.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return back();
    }
}
