<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AamarpayMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('POST')) {
            $optionals = json_decode(base64_decode($request->opt_a), true);
            $userId = $optionals['user_id'] ?? null;
            if (!is_null($userId)) {
                Auth::loginUsingId($userId);
            }

            $request->merge([
                'user_id' => $userId,
                'return_url' => $optionals['return_url'] ?? null
            ]);
        }
        return $next($request);
    }
}
