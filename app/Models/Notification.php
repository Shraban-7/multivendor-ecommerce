<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const TARGET_ORDER = 'order';
    const TARGET_PRODUCT = 'product';
    const TARGET_CHAT = 'chat';
    const TARGET_PROMOTION = 'promotion';
}
