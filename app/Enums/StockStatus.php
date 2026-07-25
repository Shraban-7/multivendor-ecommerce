<?php

namespace App\Enums;

enum StockStatus: string
{
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case PRE_ORDER = 'pre_order';

    public function label(): string
    {
        return match ($this) {
            self::IN_STOCK => 'In Stock',
            self::OUT_OF_STOCK => 'Out of Stock',
            self::PRE_ORDER => 'Pre Order',
        };
    }

    public static function labels(): array
    {
        return [
            self::IN_STOCK->label(),
            self::OUT_OF_STOCK->label(),
            self::PRE_ORDER->label(),
        ];
    }
}
