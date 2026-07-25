<?php

namespace App\Enums;

enum UserRole: int
{
    case CUSTOMER = 0;
    case AFFILIATE = 1;

    public function title()
    {
        return match ($this) {
            $this::CUSTOMER => 'Customer',
            $this::AFFILIATE => 'Affiliate',
        };
    }

    public function label()
    {
        return match ($this) {
            $this::CUSTOMER => 'customer',
            $this::AFFILIATE => 'affiliate',
        };
    }
}
