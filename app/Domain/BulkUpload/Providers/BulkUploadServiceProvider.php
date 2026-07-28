<?php

namespace App\Domain\BulkUpload\Providers;

use Illuminate\Support\ServiceProvider;

class BulkUploadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
