<?php

namespace App\Domain\Media\Providers;

use App\Domain\Media\Repositories\Contracts\ImageStorageInterface;
use App\Domain\Media\Services\MediaService;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ImageStorageInterface::class, MediaService::class);
    }
}
