<?php

namespace App\Domain\Affiliate\Providers;

use App\Domain\Affiliate\Repositories\Contracts\AffiliateRepositoryInterface;
use App\Domain\Affiliate\Repositories\EloquentAffiliateRepository;
use Illuminate\Support\ServiceProvider;

class AffiliateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AffiliateRepositoryInterface::class, EloquentAffiliateRepository::class);
    }
}
