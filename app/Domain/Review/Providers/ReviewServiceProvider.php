<?php

namespace App\Domain\Review\Providers;

use App\Domain\Review\Repositories\Contracts\ReviewRepositoryInterface;
use App\Domain\Review\Repositories\EloquentReviewRepository;
use Illuminate\Support\ServiceProvider;

class ReviewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ReviewRepositoryInterface::class, EloquentReviewRepository::class);
    }
}
