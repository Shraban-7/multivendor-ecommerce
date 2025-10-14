<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const EMAIL_VERIFICATION = 'email_verification';
    const PASSWORD_RESET = 'password_reset';
    const EXPIRY_MINUTES = 5;
    
    public static function generateCode()
    {
        return rand(100000, 999999);

        //return strtoupper(Str::random(6));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
