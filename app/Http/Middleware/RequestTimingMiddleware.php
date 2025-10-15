<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestTimingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = microtime(true) - $start;

        if ($duration > 3) {
            Log::warning(sprintf(
                'Slow request: %s %s took %.2f seconds from IP %s',
                $request->method(),
                $request->path(),
                $duration,
                $request->ip()
            ));
        }

        return $response;
    }
}
