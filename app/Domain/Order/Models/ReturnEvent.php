<?php

namespace App\Domain\Order\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\ReturnEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $return_request_id
 * @property string $type
 * @property string $actor_type
 * @property int|null $actor_id
 * @property string|null $from_state
 * @property string|null $to_state
 * @property string|null $note
 * @property array|null $meta
 * @property Carbon $created_at
 */
class ReturnEvent extends Model
{
    public $timestamps = false;

    protected $table = 'return_events';

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ReturnEventType::class,
        'meta' => 'array',
        'created_at' => 'datetime',
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    public function adminActor(): ?User
    {
        if ($this->actor_type !== 'admin') {
            return null;
        }

        return User::find($this->actor_id);
    }

    public static function log(
        ReturnRequest $return,
        string $type,
        string $actorType,
        ?int $actorId = null,
        ?string $from = null,
        ?string $to = null,
        ?string $note = null,
        array $meta = [],
    ): self {
        return static::create([
            'return_request_id' => $return->id,
            'type' => $type,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'from_state' => $from,
            'to_state' => $to,
            'note' => $note,
            'meta' => $meta,
        ]);
    }
}
