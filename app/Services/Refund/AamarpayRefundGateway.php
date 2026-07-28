<?php

namespace App\Services\Refund;

use App\Domain\Order\Models\RefundTransaction;
use RuntimeException;

class AamarpayRefundGateway
{
    public function refund(RefundTransaction $refund): array
    {
        throw new RuntimeException('aamarPay refund integration pending; configure aamarPay refund endpoint.');
    }
}
