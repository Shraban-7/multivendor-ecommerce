<?php

namespace App\Domain\Order\Enums;

enum DisputeStatus: string
{
    case OPEN = 'open';
    case UNDER_REVIEW = 'under_review';
    case SELLER_RESPONSE = 'seller_response';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::UNDER_REVIEW => 'Under Review',
            self::SELLER_RESPONSE => 'Awaiting Admin',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
        };
    }

    public function isOpen(): bool
    {
        return in_array($this, [self::OPEN, self::UNDER_REVIEW, self::SELLER_RESPONSE], true);
    }
}
