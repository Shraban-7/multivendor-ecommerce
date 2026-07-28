<?php

namespace App\Domain\Vendor\Enums;

enum PerformanceTier: string
{
    case EXCELLENT = 'excellent';
    case GOOD = 'good';
    case AVERAGE = 'average';
    case POOR = 'poor';
    case NEW = 'new';

    public function label(): string
    {
        return match ($this) {
            self::EXCELLENT => 'Excellent',
            self::GOOD => 'Good',
            self::AVERAGE => 'Average',
            self::POOR => 'Poor',
            self::NEW => 'New Seller',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::EXCELLENT => 'success',
            self::GOOD => 'primary',
            self::AVERAGE => 'warning',
            self::POOR => 'danger',
            self::NEW => 'secondary',
        };
    }

    public static function fromScore(float $score, int $orderCount): self
    {
        if ($orderCount < 5) {
            return self::NEW;
        }

        return match (true) {
            $score >= 85 => self::EXCELLENT,
            $score >= 70 => self::GOOD,
            $score >= 50 => self::AVERAGE,
            default => self::POOR,
        };
    }
}
