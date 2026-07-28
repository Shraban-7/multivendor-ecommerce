<?php

namespace App\Domain\Review\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'replied_at' => 'datetime',
        'is_approved' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function reports()
    {
        return $this->hasMany(ReportReview::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }

    public function scopeForSeller(Builder $query, int $sellerId): Builder
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeHasReply(Builder $query): Builder
    {
        return $query->whereNotNull('seller_reply');
    }

    public function scopeWithoutReply(Builder $query): Builder
    {
        return $query->whereNull('seller_reply');
    }

    public function scopeRating(Builder $query, int $rating): Builder
    {
        return $query->where('rating', $rating);
    }

    public function hasReply(): bool
    {
        return $this->seller_reply !== null;
    }
}
