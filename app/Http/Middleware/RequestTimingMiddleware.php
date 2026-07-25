<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestTimingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $response = $next($request);

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $duration = $endTime - $startTime;
        $memoryUsed = $endMemory - $startMemory;

        // Request metadata
        $method = $request->method();
        $uri = $request->path();
        $ip = $request->ip();

        // Payload info
        $payload = $request->all();
        $payloadSize = strlen(json_encode($payload));

        // Response info
        $statusCode = $response->getStatusCode();

        // Log only slow requests or large payloads
        if ($duration > 3 || $payloadSize > 1024 * 50) { // 3 sec or > 50 KB
            Log::warning('⚠️ Slow or heavy request detected', [
                'method' => $method,
                'uri' => $uri,
                'ip' => $ip,
                'duration_sec' => round($duration, 3),
                'memory_used_mb' => round($memoryUsed / 1024 / 1024, 2),
                'payload_size_kb' => round($payloadSize / 1024, 2),
                'status_code' => $statusCode,
                'payload' => $payload,
            ]);
        }

        return $response;
    }
}
