<?php

namespace App\Domain\Payment\Services;

use App\Domain\Payment\Contracts\PaymentGatewayInterface;
use App\Services\BkashService;

class BkashGateway implements PaymentGatewayInterface
{
    public function __construct(protected BkashService $bkashService) {}

    public function createPayment(float $amount, string $invoiceNumber): array
    {
        return $this->bkashService->createPayment($amount, $invoiceNumber);
    }

    public function executePayment(string $paymentId): array
    {
        return $this->bkashService->executePayment($paymentId);
    }

    public function queryPayment(string $paymentId): array
    {
        return $this->bkashService->queryPayment($paymentId);
    }
}
