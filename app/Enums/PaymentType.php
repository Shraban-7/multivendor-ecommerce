<?php

namespace App\Enums;

enum PaymentType: int
{
    case FULL_PAYMENT = 1;
    case COD_ONLY = 2;
    case COD_WITH_DELIVERY_CHARGE = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function title(): string
    {
        return match ($this) {
            self::FULL_PAYMENT => 'Full Payment',
            self::COD_ONLY => 'Cash on Delivery',
            self::COD_WITH_DELIVERY_CHARGE => 'COD (Prepaid Delivery)',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::FULL_PAYMENT => 'Pay full amount online while ordering.',
            self::COD_ONLY => 'Pay full amount in cash upon delivery.',
            self::COD_WITH_DELIVERY_CHARGE => 'Pay delivery charge online, rest on delivery.',
        };
    }
}
