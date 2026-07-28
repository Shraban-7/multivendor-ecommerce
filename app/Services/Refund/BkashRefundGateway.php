<?php

namespace App\Services\Refund;

use App\Domain\Order\Models\RefundTransaction;
use RuntimeException;

class BkashRefundGateway
{
    public function refund(RefundTransaction $refund): array
    {
        throw new RuntimeException('bKash refund integration pending; configure bKash tokenized refund API.');
    }
}
