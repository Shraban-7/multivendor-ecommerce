<?php

namespace App\Domain\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequestItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer',
        'refund_amount' => 'float',
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
