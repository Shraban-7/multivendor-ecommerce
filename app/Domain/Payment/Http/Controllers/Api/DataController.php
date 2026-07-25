<?php

namespace App\Domain\Payment\Http\Controllers\Api;

use App\Domain\Payment\Http\Resources\PaymentGatewayResource;
use App\Domain\Payment\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DataController extends Controller
{
    public function paymentGateways()
    {
        $paymentGateways = Cache::remember('payment_gateways:enabled', 86400, function () {
            return PaymentGateway::where('is_enabled', true)->get();
        });

        return apiResourceResponse(PaymentGatewayResource::collection($paymentGateways));
    }
}
