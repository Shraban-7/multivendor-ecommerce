<?php

namespace App\Domain\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const EMAIL_VERIFICATION = 'email_verification';

    const PASSWORD_RESET = 'password_reset';

    const EXPIRY_MINUTES = 5;

    public static function generateCode()
    {
        return 123456;

        return rand(100000, 999999);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
