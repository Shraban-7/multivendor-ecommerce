<?php

namespace App\Domain\Order\Enums;

enum OrderStatus: int
{
    case PENDING = 0;
    case ACCEPTED = 1;
    case SHIPPED = 2;
    case DELIVERED = 3;
    case COMPLETED = 4;
    case CANCELLED = 5;
    case RETURN_REQUESTED = 6;
    case RETURN_APPROVED = 7;
    case RETURNED = 8;
    case REFUNDED = 9;

    public function label(): string
    {
        return match ($this) {
            $this::PENDING => 'pending',
            $this::ACCEPTED => 'accepted',
            $this::SHIPPED => 'shipped',
            $this::DELIVERED => 'delivered',
            $this::COMPLETED => 'completed',
            $this::CANCELLED => 'cancelled',
            $this::RETURN_REQUESTED => 'return_requested',
            $this::RETURN_APPROVED => 'return_approved',
            $this::RETURNED => 'returned',
            $this::REFUNDED => 'refunded',
        };
    }

    public function title(): string
    {
        return ucwords(str_replace('_', ' ', $this->label()));
    }

    public static function labels(): array
    {
        return [
            self::PENDING->label(),
            self::ACCEPTED->label(),
            self::SHIPPED->label(),
            self::DELIVERED->label(),
            self::COMPLETED->label(),
            self::CANCELLED->label(),
            self::RETURN_REQUESTED->label(),
            self::RETURN_APPROVED->label(),
            self::RETURNED->label(),
            self::REFUNDED->label(),
        ];
    }

    public static function valueFromLabel(string $label): ?int
    {
        return collect(self::cases())
            ->first(fn (self $case) => $case->label() === $label)?->value;
    }
}
