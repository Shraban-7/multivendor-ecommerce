<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const PENDING = 1;
    const SHIPPED = 2;
    const DELIVERED = 3;
    const CANCELLED = 4;

}
