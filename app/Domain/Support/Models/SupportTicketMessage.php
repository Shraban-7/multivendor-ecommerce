<?php

namespace App\Domain\Support\Models;

use App\Domain\Auth\Models\Admin;
use App\Domain\Auth\Models\User;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $support_ticket_id
 * @property string $sender_type
 * @property int|null $sender_id
 * @property string $body
 * @property bool $is_internal_note
 * @property bool $is_status_change
 * @property array|null $meta
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SupportTicketMessage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_internal_note' => 'boolean',
        'is_status_change' => 'boolean',
        'meta' => 'array',
        'read_at' => 'datetime',
    ];

    public const SENDER_SELLER = 'seller';

    public const SENDER_CUSTOMER = 'customer';

    public const SENDER_ADMIN = 'admin';

    public const SENDER_SYSTEM = 'system';

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(SupportTicketAttachment::class);
    }

    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    public function sellerSender(): ?Seller
    {
        return $this->sender_type === self::SENDER_SELLER
            ? Seller::find($this->sender_id)
            : null;
    }

    public function adminSender(): ?Admin
    {
        return $this->sender_type === self::SENDER_ADMIN
            ? Admin::find($this->sender_id)
            : null;
    }

    public function userSender(): ?User
    {
        return $this->sender_type === self::SENDER_CUSTOMER
            ? User::find($this->sender_id)
            : null;
    }

    public function isFromSeller(): bool
    {
        return $this->sender_type === self::SENDER_SELLER;
    }

    public function isFromAdmin(): bool
    {
        return $this->sender_type === self::SENDER_ADMIN;
    }

    public function isFromCustomer(): bool
    {
        return $this->sender_type === self::SENDER_CUSTOMER;
    }

    public function markRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }
}
