<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerChat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function messages(): HasMany
    {
        return $this->hasMany(SellerChatMessage::class);
    }
}
