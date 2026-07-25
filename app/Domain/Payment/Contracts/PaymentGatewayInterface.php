<?php

namespace App\Domain\Payment\Contracts;

interface PaymentGatewayInterface
{
    /**
     * @return array<string, mixed>
     */
    public function createPayment(float $amount, string $invoiceNumber): array;

    /**
     * @return array<string, mixed>
     */
    public function executePayment(string $paymentId): array;

    /**
     * @return array<string, mixed>
     */
    public function queryPayment(string $paymentId): array;
}
