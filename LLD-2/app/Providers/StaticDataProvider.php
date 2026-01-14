<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use app\Services\StaticDataService;

class StaticDataProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StaticDataService::class, function ($app) {
            return new StaticDataService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
