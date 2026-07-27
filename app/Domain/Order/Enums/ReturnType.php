<?php

namespace App\Domain\Order\Enums;

enum ReturnType: string
{
    case FULL = 'full';
    case PARTIAL = 'partial';
    case EXCHANGE = 'exchange';

    public function label(): string
    {
        return match ($this) {
            self::FULL => 'Full Refund',
            self::PARTIAL => 'Partial Refund',
            self::EXCHANGE => 'Exchange',
        };
    }

    public function requiresItems(): bool
    {
        return $this !== self::FULL;
    }
}
