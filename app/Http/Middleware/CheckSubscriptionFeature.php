<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscriptionFeature
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = seller();

        if (! $user || ! $user->hasFeature($feature)) {
            return redirect()->back()->with('error', 'Your plan does not allow access to this feature.');
        }

        return $next($request);
    }
}
