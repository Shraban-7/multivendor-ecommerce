<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('seller_id', Auth::guard('seller')->id())->latest('id')->paginate(20);

        return view('seller.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::guard('seller')->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return back();
    }
}
