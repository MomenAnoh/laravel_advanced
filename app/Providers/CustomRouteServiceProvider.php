<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class CustomRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 5 request only
        // custom_limit  name of user it in routes
        // by($request->ip()) -> limit by ip
        RateLimiter::for('custom_limit',function (Request $request){
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
