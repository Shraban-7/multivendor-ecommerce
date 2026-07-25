<?php

use App\Domain\Affiliate\Http\Middleware\AffiliateReferralMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AutoMarkNotificationsAsRead;
use App\Http\Middleware\CheckSubscriptionFeature;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RequestTimingMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));

            Route::middleware('web')
                ->group(base_path('routes/seller.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/frontend.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            TrustProxies::class,
            HandleCors::class,
            PreventRequestsDuringMaintenance::class,
            ValidatePostSize::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
            RequestTimingMiddleware::class,
        ]);

        $middleware->web(append: [
            AffiliateReferralMiddleware::class,
        ]);

        // External payment gateways POST to these callbacks without a CSRF token.
        $middleware->validateCsrfTokens(except: [
            'payment/success',
            'payment/cancelled',
            'payment/failed',
            'payment/ipn',
        ]);

        $middleware->alias([
            'seller' => SellerMiddleware::class,
            'admin' => AdminMiddleware::class,
            'markReadAuto' => AutoMarkNotificationsAsRead::class,
            'subscription.feature' => CheckSubscriptionFeature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
