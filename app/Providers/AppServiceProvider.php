<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\ServiceRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\OrderRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
