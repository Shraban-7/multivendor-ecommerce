<?php

namespace App\Domain\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $support_ticket_message_id
 * @property string $disk
 * @property string $path
 * @property string $original_name
 * @property string|null $mime
 * @property int|null $size
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SupportTicketAttachment extends Model
{
    protected $guarded = ['id'];

    public function message(): BelongsTo
    {
        return $this->belongsTo(SupportTicketMessage::class, 'support_ticket_message_id');
    }

    public function url(): string
    {
        return asset('storage/'.ltrim($this->path, '/'));
    }
}
