<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\DosenRepositoryInterface::class,
            \App\Repositories\DosenRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\BidangPenelitianRepositoryInterface::class,
            \App\Repositories\BidangPenelitianRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\RiwayatKlasifikasiRepositoryInterface::class,
            \App\Repositories\RiwayatKlasifikasiRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
