<?php

namespace App\Enums;

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
            static::PENDING->label(),
            static::ACCEPTED->label(),
            static::SHIPPED->label(),
            static::DELIVERED->label(),
            static::COMPLETED->label(),
            static::CANCELLED->label(),
            static::RETURN_REQUESTED->label(),
            static::RETURN_APPROVED->label(),
            static::RETURNED->label(),
            static::REFUNDED->label(),
        ];
    }

    public static function valueFromLabel(string $label): ?int
    {
        return collect(self::cases())
            ->first(fn(self $case) => $case->label() === $label)?->value;
    }
}
