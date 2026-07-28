<?php

namespace App\Domain\Support\Enums;

enum TicketStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case AWAITING_SELLER = 'awaiting_seller';
    case AWAITING_ADMIN = 'awaiting_admin';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In Progress',
            self::AWAITING_SELLER => 'Awaiting Seller Reply',
            self::AWAITING_ADMIN => 'Awaiting Admin Reply',
            self::RESOLVED => 'Resolved',
            self::CLOSED => 'Closed',
            self::REJECTED => 'Rejected',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OPEN => 'warning',
            self::IN_PROGRESS => 'primary',
            self::AWAITING_SELLER => 'info',
            self::AWAITING_ADMIN => 'info',
            self::RESOLVED => 'success',
            self::CLOSED => 'secondary',
            self::REJECTED => 'danger',
        };
    }

    public function isClosed(): bool
    {
        return in_array($this, [self::RESOLVED, self::CLOSED, self::REJECTED], true);
    }

    public function isOpen(): bool
    {
        return ! $this->isClosed();
    }

    public function awaiting(string $actorType): string
    {
        return match ($actorType) {
            'seller' => self::AWAITING_ADMIN,
            'admin' => self::AWAITING_SELLER,
            default => self::IN_PROGRESS,
        };
    }
}
