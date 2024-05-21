<?php

namespace App\Providers;

use App\Services\Impl\ProductServiceImpl;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register binding for ProductService
        $this->app->bind(ProductService::class, ProductServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
