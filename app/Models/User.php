<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use App\Mail\EmailVerificationMail;
use App\Mail\WelcomeMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->referral_code = static::generateReferralCode();
        });
    }

    public static function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(str_replace('.', '', uniqid()), -8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public static function generateShortUsername(?string $phone = null): string
    {
        $last4 = $phone ? substr($phone, -4) : rand(1000, 9999);

        // 2 random letters
        $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 2);

        $username = 'u'.$last4.$random;

        while (self::where('username', $username)->exists()) {
            $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 2);
            $username = 'u'.$last4.$random;
        }

        return $username;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getAvatarAttribute()
    {
        return is_null($this->attributes['image']) ? asset('assets/frontend/images/user-avatar-1.png') : storage_url($this->attributes['image']);
    }

    public function followedSellers()
    {
        return $this->belongsToMany(Seller::class, 'seller_followers', 'user_id', 'seller_id');
    }

    public function isAffiliate()
    {
        return $this->role === UserRole::AFFILIATE->value;
    }

    public function sendEmailVerificationMail()
    {
        $code = VerificationCode::generateCode();

        VerificationCode::create([
            'email' => $this->email,
            'code' => VerificationCode::generateCode(),
            'type' => VerificationCode::EMAIL_VERIFICATION,
            'expires_at' => now()->addMinutes(VerificationCode::EXPIRY_MINUTES),
        ]);

        Mail::to($this->email)->queue(new EmailVerificationMail(
            $this->name,
            $code,
            VerificationCode::EXPIRY_MINUTES
        ));
    }

    public function sendWelcomeMail()
    {
        Mail::to($this->email)->queue(new WelcomeMail($this->name));
    }

    public static function phoneValidationRules($unique = false)
    {
        // 'required|string|regex:/^\+8801[3-9]\d{8}$/|max:14',
        $rules = 'required|string|regex:/^01[3-9]\d{8}$/|size:11';

        if ($unique) {
            $rules .= '|unique:users,phone';
        }

        return $rules;
    }
}
