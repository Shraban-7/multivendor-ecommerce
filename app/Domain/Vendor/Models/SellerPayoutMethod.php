<?php

namespace App\Domain\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerPayoutMethod extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function scopeDefaults($query)
    {
        return $query->where('is_default', true);
    }

    public static function methodTypes(): array
    {
        return [
            'bank' => 'Bank Account',
            'mobile_banking' => 'Mobile Banking',
            'cash' => 'Cash',
        ];
    }

    public static function mobileProviders(): array
    {
        return [
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
            'upay' => 'Upay',
        ];
    }

    public function maskedAccountNumber(): string
    {
        $len = strlen($this->account_number);
        if ($len <= 4) {
            return $this->account_number;
        }

        return str_repeat('*', $len - 4).substr($this->account_number, -4);
    }

    public function methodLabel(): string
    {
        return self::methodTypes()[$this->method_type] ?? ucfirst($this->method_type);
    }
}
