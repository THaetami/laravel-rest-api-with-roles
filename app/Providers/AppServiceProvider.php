<?php

namespace App\Providers;

use App\Services\Impl\PaginationServiceImpl;
use App\Services\Impl\ProductServiceImpl;
use App\Services\Impl\TransactionServiceImpl;
use App\Services\PaginationService;
use App\Services\ProductService;
use App\Services\TransactionService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register binding for ProductService
        // $this->app->bind(ProductService::class, ProductServiceImpl::class);
        $this->app->bind(PaginationService::class, PaginationServiceImpl::class);
        // $this->app->bind(TransactionService::class, TransactionServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
