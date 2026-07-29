<?php

namespace App\Domain\Payment\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const PENDING = 'Pending';

    const SUCCESSFUL = 'Successful';

    const FAILED = 'Failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
