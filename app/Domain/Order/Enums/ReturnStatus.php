<?php

namespace App\Domain\Order\Enums;

enum ReturnStatus: string
{
    case PENDING = 'pending';
    case AWAITING_SHIPMENT = 'awaiting_shipment';
    case ITEM_RECEIVED = 'item_received';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REFUND_INITIATED = 'refund_initiated';
    case REFUNDED = 'refunded';
    case EXCHANGE_SHIPPED = 'exchange_shipped';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::AWAITING_SHIPMENT => 'Awaiting Shipment',
            self::ITEM_RECEIVED => 'Item Received',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::REFUND_INITIATED => 'Refund Initiated',
            self::REFUNDED => 'Refunded',
            self::EXCHANGE_SHIPPED => 'Exchange Shipped',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING, self::AWAITING_SHIPMENT => '#d97706',
            self::ITEM_RECEIVED, self::EXCHANGE_SHIPPED => '#2563eb',
            self::APPROVED => '#2563eb',
            self::REFUND_INITIATED => '#7c3aed',
            self::REFUNDED, self::COMPLETED => '#059669',
            self::REJECTED, self::CANCELLED => '#dc2626',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [
            self::REFUNDED,
            self::COMPLETED,
            self::CANCELLED,
            self::REJECTED,
        ], true);
    }

    public function isRefundable(): bool
    {
        return in_array($this, [
            self::APPROVED,
            self::ITEM_RECEIVED,
            self::REFUND_INITIATED,
        ], true);
    }
}
