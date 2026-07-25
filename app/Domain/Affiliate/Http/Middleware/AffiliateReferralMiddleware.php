<?php

namespace App\Domain\Affiliate\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AffiliateReferralMiddleware
{
    protected $cookieName = 'affiliate_refs';

    protected $cookieDuration = 60 * 24 * 30;

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $refCode = $request->query('ref');

        $segments = $request->segments();

        $productSlug = null;
        if (count($segments) >= 2 && $segments[0] === 'products') {
            $productSlug = $segments[1];
        }

        if ($refCode && $productSlug) {
            $cookieValue = $request->cookie($this->cookieName);

            $affiliateRefs = [];

            if ($cookieValue) {
                $affiliateRefs = json_decode($cookieValue, true) ?: [];
            }

            if (! isset($affiliateRefs[$productSlug])) {
                $affiliateRefs[$productSlug] = [];
            }

            if (! in_array($refCode, $affiliateRefs[$productSlug])) {
                $affiliateRefs[$productSlug][] = $refCode;
            }

            $newCookieValue = json_encode($affiliateRefs);

            if ($cookieValue !== $newCookieValue) {
                $cookie = cookie(
                    $this->cookieName,
                    $newCookieValue,
                    $this->cookieDuration,
                    '/',
                    null,
                    false,
                    true
                );

                $response->headers->setCookie($cookie);
            }
        }

        return $response;
    }
}
