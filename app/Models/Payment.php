<?php

namespace App\Models;

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
}
