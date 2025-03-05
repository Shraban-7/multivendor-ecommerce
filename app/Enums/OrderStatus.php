<?php

namespace App\Enums;

enum OrderStatus : int
{
    case PENDING = 0;
    case SHIPPED = 1;
    case DELIVERED = 2;
    case CANCELLED = 3;

    public function label(): string {
        return match($this) {
            $this::PENDING => 'pending',
            $this::SHIPPED => 'shipped',
            $this::DELIVERED => 'delivered',
            $this::CANCELLED => 'cancelled',
        };
    }

    public static function labels(): array
    {
        return [
            static::PENDING->label(),
            static::SHIPPED->label(),
            static::DELIVERED->label(),
            static::CANCELLED->label(),
        ];
    }
}