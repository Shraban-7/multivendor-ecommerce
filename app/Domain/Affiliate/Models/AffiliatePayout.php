<?php

namespace App\Domain\Affiliate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliatePayout extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const PENDING = 0;

    const COMPLETED = 1;

    const FAILED = 2;

    public function label(): string
    {
        return match ($this->status) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::PENDING => 'yellow',
            self::COMPLETED => 'green',
            self::FAILED => 'red',
        };
    }

    public static function payment_methods()
    {
        return [
            'bank' => 'Bank',
            'bkash' => 'Bkash',
            'nagad' => 'Nagad',
        ];
    }
}
