<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const EMAIL_VERIFICATION = 'email_verification';
    const PASSWORD_RESET = 'password_reset';
}
