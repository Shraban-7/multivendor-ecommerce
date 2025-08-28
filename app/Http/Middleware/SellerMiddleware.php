<?php
namespace App\Http\Middleware;

use App\Models\SellerEmployee;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $seller = Auth::guard('seller')->user();

        $employee = Auth::guard('employee')->user();

        if (($seller && $seller->is_active == 1) || ($employee && $employee->is_active == 1)) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
