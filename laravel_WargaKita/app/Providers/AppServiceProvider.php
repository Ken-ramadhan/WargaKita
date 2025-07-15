<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Carbon::setLocale('id');
        // Di AppServiceProvider.php (atau file helper lainnya)
        // Anda bisa membuat helper custom misalnya
    }
}
