<?php

namespace App\Domain\Support\Models;

use App\Domain\Auth\Models\Admin;
use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Domain\Support\Enums\TicketCategory;
use App\Domain\Support\Enums\TicketPriority;
use App\Domain\Support\Enums\TicketStatus;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $ticket_number
 * @property string $subject
 * @property string $description
 * @property int|null $seller_id
 * @property int|null $user_id
 * @property string $category
 * @property string $priority
 * @property string $status
 * @property int|null $assigned_admin_id
 * @property int|null $order_id
 * @property Carbon|null $first_admin_reply_at
 * @property Carbon|null $seller_last_reply_at
 * @property Carbon|null $admin_last_reply_at
 * @property Carbon|null $sla_due_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $last_message_at
 * @property int $reply_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SupportTicket extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => TicketStatus::class,
        'priority' => TicketPriority::class,
        'category' => TicketCategory::class,
        'sla_due_at' => 'datetime',
        'first_admin_reply_at' => 'datetime',
        'seller_last_reply_at' => 'datetime',
        'admin_last_reply_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_message_at' => 'datetime',
        'reply_count' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(SupportTicketMessage::class)->latestOfMany('created_at');
    }

    public function visibleMessages(): HasMany
    {
        return $this->messages()->where(function (Builder $q) {
            $q->where('is_internal_note', false)
                ->orWhere(function (Builder $q2) {
                    // For internal queries keep internal notes visible too
                    $q2->where('is_internal_note', true);
                });
        })->orderBy('created_at');
    }

    public function events(): HasMany
    {
        return $this->hasMany(SupportTicketEvent::class);
    }

    public function scopeForSeller(Builder $query, int $sellerId): Builder
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAssignedTo(Builder $query, int $adminId): Builder
    {
        return $query->where('assigned_admin_id', $adminId);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            TicketStatus::RESOLVED->value,
            TicketStatus::CLOSED->value,
            TicketStatus::REJECTED->value,
        ]);
    }

    public function scopeAwaiting(Builder $query, string $actor): Builder
    {
        $state = match ($actor) {
            'admin', 'seller' => $actor === 'admin'
                ? TicketStatus::AWAITING_ADMIN
                : TicketStatus::AWAITING_SELLER,
            default => null,
        };

        return $state ? $query->where('status', $state->value) : $query;
    }

    public function statusColor(): string
    {
        return $this->status instanceof TicketStatus
            ? $this->status->color()
            : 'secondary';
    }

    public function statusLabel(): string
    {
        return $this->status instanceof TicketStatus
            ? $this->status->label()
            : ucfirst((string) $this->status);
    }

    public function priorityColor(): string
    {
        return $this->priority instanceof TicketPriority
            ? $this->priority->color()
            : 'secondary';
    }

    public function isOpen(): bool
    {
        return ! ($this->status instanceof TicketStatus && $this->status->isClosed());
    }

    public function isOverdue(): bool
    {
        return $this->sla_due_at && $this->sla_due_at->isPast() && $this->isOpen();
    }

    public function requiresAdminFirstReply(): bool
    {
        return $this->first_admin_reply_at === null && $this->isOpen();
    }
}
