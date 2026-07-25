<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoMarkNotificationsAsRead
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (! auth()->check()) {
            return;
        }

        $ids = $request->attributes->get('notifications_to_mark_read', []);

        if (! empty($ids)) {
            Notification::whereIn('id', $ids)
                ->update(['is_read' => true]);
        }
    }
}
