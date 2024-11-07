<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\IBookingRepository;
use App\Repositories\Contracts\ICategoryRepository;
use App\Repositories\Contracts\ISellerRepository;
use App\Repositories\Contracts\ITicketRepository;
use App\Repositories\SellerRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ITicketRepository::class, TicketRepository::class);
        $this->app->singleton(ICategoryRepository::class, CategoryRepository::class);
        $this->app->singleton(IBookingRepository::class, BookingRepository::class);
        $this->app->singleton(ISellerRepository::class, SellerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
