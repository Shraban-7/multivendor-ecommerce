<?php

namespace App\Domain\Order\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\ReturnType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ReturnType::class,
        'approved_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function needsExchangeNote(): bool
    {
        return $this->type === ReturnType::EXCHANGE;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnRequestItem::class);
    }

    public function isFull(): bool
    {
        return $this->type === ReturnType::FULL;
    }

    public function isPartial(): bool
    {
        return $this->type === ReturnType::PARTIAL;
    }

    public function isExchange(): bool
    {
        return $this->type === ReturnType::EXCHANGE;
    }

    public function typeLabel(): string
    {
        return $this->type?->label() ?? 'Full Refund';
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'refunded' => 'blue',
            default => 'gray',
        };
    }

    public function label(): string
    {
        return ucfirst($this->status);
    }
}
