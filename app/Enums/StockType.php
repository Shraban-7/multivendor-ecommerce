<?php

namespace App\Enums;

enum StockType: int
{
    case SET_EXACT_STOCK = 0;
    case ADD_STOCK = 1;
    case REMOVE_STOCK = 2;

    public function label(): string
    {
        return match ($this) {
            $this::SET_EXACT_STOCK => 'Set Exact Stock',
            $this::ADD_STOCK => 'Add Stock',
            $this::REMOVE_STOCK => 'Remove Stock',
        };
    }

    public static function labels(): array
    {
        return [
            self::SET_EXACT_STOCK->label(),
            self::ADD_STOCK->label(),
            self::REMOVE_STOCK->label(),
        ];
    }
}
