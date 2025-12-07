<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const TYPE_SIGNUP = 'signup';
    public const TYPE_LOGIN = 'login';
    public const TYPE_PASSWORD_RESET = 'password_reset';

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public static function generate(string $identifier, string $type, int $length = 6, int $validMinutes = 5): self
    {
        //$code = str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        $code = 123456;

        return self::create([
            'identifier'  => $identifier,
            'code' => $code,
            'type' => $type,
            'expires_at' => now()->addMinutes($validMinutes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function markUsed(): void
    {
        $this->update(['used' => true]);
    }

    public static function verify(string $identifier, string $code, string $type): ?self
    {
        $otp = self::where('identifier', $identifier)
            ->where('code', $code)
            ->where('type', $type)
            ->where('used', false)
            ->orderBy('id', 'DESC')
            ->first();

        if (! $otp) {
            return null;
        }

        if ($otp->isExpired()) {
            return null;
        }

        $otp->markUsed();

        return $otp;
    }

    public function scopeActive($query)
    {
        return $query->where('used', false)->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeForIdentifier($query, string $identifier)
    {
        return $query->where('identifier', $identifier);
    }

    public static function expireAll(string $identifier, string $type): int
    {
        return self::where('identifier', $identifier)
            ->where('type', $type)
            ->where('used', false)
            ->update(['expires_at' => now()]);
    }

    public static function cleanupExpired(int $minutesOld = 30): int
    {
        return self::where('expires_at', '<=', now()->subMinutes($minutesOld))->delete();
    }

    public static function tooManyRequests(string $identifier, int $limit = 5, int $perMinutes = 60): bool
    {
        return self::where('identifier', $identifier)->where('created_at', '>=', now()->subMinutes($perMinutes))->count() >= $limit;
    }
}
