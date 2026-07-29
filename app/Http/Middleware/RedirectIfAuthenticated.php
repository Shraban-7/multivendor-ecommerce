<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Redirect already-authenticated visitors away from guest-only pages.
     * Sellers/admins go to their dashboards; customers go home.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards)
            ? ['web', 'seller', 'employee', 'admin']
            : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->to($this->redirectPath($guard));
            }
        }

        return $next($request);
    }

    protected function redirectPath(?string $guard): string
    {
        return match ($guard) {
            'admin' => route('admin.dashboard'),
            'seller', 'employee' => route('seller.dashboard'),
            default => route('home'),
        };
    }
}
