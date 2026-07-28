<?php

namespace App\Domain\Vendor\Enums;

use Illuminate\Support\Carbon;

enum PerformancePeriod: string
{
    case LAST_7_DAYS = 'last_7_days';
    case LAST_30_DAYS = 'last_30_days';
    case LAST_90_DAYS = 'last_90_days';
    case ALL_TIME = 'all_time';

    public function label(): string
    {
        return match ($this) {
            self::LAST_7_DAYS => 'Last 7 days',
            self::LAST_30_DAYS => 'Last 30 days',
            self::LAST_90_DAYS => 'Last 90 days',
            self::ALL_TIME => 'All time',
        };
    }

    public function days(): ?int
    {
        return match ($this) {
            self::LAST_7_DAYS => 7,
            self::LAST_30_DAYS => 30,
            self::LAST_90_DAYS => 90,
            self::ALL_TIME => null,
        };
    }

    public function start(?Carbon $now = null): ?Carbon
    {
        $now ??= now();
        $days = $this->days();

        return $days === null ? null : $now->copy()->subDays($days);
    }
}
