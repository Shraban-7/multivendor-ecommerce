<?php

namespace App\Domain\Support\Enums;

enum TicketCategory: string
{
    case ACCOUNT = 'account';
    case PAYMENT = 'payment';
    case ORDER = 'order';
    case PRODUCT = 'product';
    case RETURN_REFUND = 'return_refund';
    case COMPLIANCE = 'compliance';
    case SUBSCRIPTION = 'subscription';
    case TECHNICAL = 'technical';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ACCOUNT => 'Account / Profile',
            self::PAYMENT => 'Payment / Payout',
            self::ORDER => 'Order',
            self::PRODUCT => 'Product / Catalog',
            self::RETURN_REFUND => 'Return / Refund',
            self::COMPLIANCE => 'Compliance / KYC',
            self::SUBSCRIPTION => 'Subscription Plan',
            self::TECHNICAL => 'Technical Issue',
            self::OTHER => 'Other',
        };
    }

    public static function labels(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->label();
        }

        return $out;
    }
}
