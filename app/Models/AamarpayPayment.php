<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AamarpayPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const PENDING = 'Pending';
    const SUCCESSFUL = 'Successful';
    const FAILED = 'Failed';
}
