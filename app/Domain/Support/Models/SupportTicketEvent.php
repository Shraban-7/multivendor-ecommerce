<?php

namespace App\Domain\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $support_ticket_id
 * @property string $type
 * @property string $actor_type
 * @property int|null $actor_id
 * @property string|null $from_value
 * @property string|null $to_value
 * @property string|null $note
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SupportTicketEvent extends Model
{
    protected $guarded = ['id'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public static function log(
        SupportTicket $ticket,
        string $type,
        string $actorType,
        ?int $actorId = null,
        ?string $from = null,
        ?string $to = null,
        ?string $note = null,
    ): self {
        return static::create([
            'support_ticket_id' => $ticket->id,
            'type' => $type,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'from_value' => $from,
            'to_value' => $to,
            'note' => $note,
        ]);
    }
}
