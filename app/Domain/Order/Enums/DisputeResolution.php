<?php

namespace App\Domain\Order\Enums;

enum DisputeResolution: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PARTIAL_REFUND = 'partial_refund';
    case WALLET_CREDIT = 'wallet_credit';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Approve Return',
            self::REJECTED => 'Reject Return',
            self::PARTIAL_REFUND => 'Partial Refund',
            self::WALLET_CREDIT => 'Wallet Credit',
        };
    }
}
