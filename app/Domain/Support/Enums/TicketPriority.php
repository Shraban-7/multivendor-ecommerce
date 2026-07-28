<?php

namespace App\Domain\Support\Enums;

enum TicketPriority: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case HIGH = 'high';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::NORMAL => 'Normal',
            self::HIGH => 'High',
            self::URGENT => 'Urgent',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => 'secondary',
            self::NORMAL => 'primary',
            self::HIGH => 'warning',
            self::URGENT => 'danger',
        };
    }

    public function weight(): int
    {
        return match ($this) {
            self::LOW => 1,
            self::NORMAL => 2,
            self::HIGH => 3,
            self::URGENT => 4,
        };
    }
}
