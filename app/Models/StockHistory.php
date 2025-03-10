<?php

namespace App\Models;

use App\Enums\StockType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => StockType::class,
    ];
}
