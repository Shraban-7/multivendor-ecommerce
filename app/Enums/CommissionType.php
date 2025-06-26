<?php
namespace App\Enums;

enum CommissionType: string {
    case FLAT       = 'flat';
    case PERCENTAGE = 'percentage';

    public function label(): string
    {
        return match ($this) {
            self::FLAT => 'Flat',
            self::PERCENTAGE => 'Percentage',
        };
    }

    public static function labels(): array
    {
        return [
            self::FLAT->label(),
            self::PERCENTAGE->label(),
        ];
    }
}
