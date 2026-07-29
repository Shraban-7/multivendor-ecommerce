<?php

namespace App\Domain\Order\Enums;

enum ReturnEventType: string
{
    case CREATED = 'created';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ITEM_SHIPPED = 'item_shipped';
    case ITEM_RECEIVED = 'item_received';
    case REFUND_INITIATED = 'refund_initiated';
    case REFUND_COMPLETED = 'refund_completed';
    case REFUND_FAILED = 'refund_failed';
    case WALLET_CREDITED = 'wallet_credited';
    case DISPUTE_OPENED = 'dispute_opened';
    case DISPUTE_RESPONSE = 'dispute_response';
    case DISPUTE_RESOLVED = 'dispute_resolved';
    case EXCHANGE_SHIPPED = 'exchange_shipped';
    case CANCELLED = 'cancelled';

    public function color(): string
    {
        return match ($this) {
            self::CREATED => '#d97706',
            self::APPROVED, self::ITEM_RECEIVED => '#2563eb',
            self::REJECTED, self::REFUND_FAILED, self::CANCELLED, self::DISPUTE_OPENED => '#dc2626',
            self::ITEM_SHIPPED, self::EXCHANGE_SHIPPED => '#7c3aed',
            self::REFUND_INITIATED, self::REFUND_COMPLETED, self::WALLET_CREDITED => '#059669',
            self::DISPUTE_RESPONSE => '#0891b2',
            self::DISPUTE_RESOLVED => '#059669',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Return Requested',
            self::APPROVED => 'Return Approved',
            self::REJECTED => 'Return Rejected',
            self::ITEM_SHIPPED => 'Item Shipped Back',
            self::ITEM_RECEIVED => 'Item Received by Seller',
            self::REFUND_INITIATED => 'Refund Initiated',
            self::REFUND_COMPLETED => 'Refund Completed',
            self::REFUND_FAILED => 'Refund Failed',
            self::WALLET_CREDITED => 'Amount Credited to Wallet',
            self::DISPUTE_OPENED => 'Dispute Opened',
            self::DISPUTE_RESPONSE => 'Seller Responded',
            self::DISPUTE_RESOLVED => 'Dispute Resolved',
            self::EXCHANGE_SHIPPED => 'Exchange Item Shipped',
            self::CANCELLED => 'Return Cancelled',
        };
    }
}
