<?php

namespace App\Enums;

enum OrderStatus : int
{
    case PENDING = 0;
    case SHIPPED = 1;
    case DELIVERED = 2;
    case CANCELLED = 3;
    case ORDER_PLACED = 5;
    case PACKAGING = 6;
    case ON_THE_ROAD = 7;

    public function label(): string {
        return match($this) {
            $this::PENDING => 'pending',
            $this::SHIPPED => 'shipped',
            $this::DELIVERED => 'delivered',
            $this::CANCELLED => 'cancelled',
            $this::ORDER_PLACED => 'order_placed',
            $this::PACKAGING => 'packaging',
            $this::ON_THE_ROAD => 'on_the_road',
        };
    }

    public static function labels(): array
    {
        return [
            static::PENDING->label(),
            static::SHIPPED->label(),
            static::DELIVERED->label(),
            static::CANCELLED->label(),
            static::ORDER_PLACED->label(),
            static::PACKAGING->label(),
            static::ON_THE_ROAD->label(),
        ];
    }
}
