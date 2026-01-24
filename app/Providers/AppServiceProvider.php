<?php

namespace App\Providers;

use App\Events\Login;
use App\Listeners\SendLoginEmailNotification;
use App\Repositories\ProductRepo;
use App\Repositories\ProductRepoInterface;
use Illuminate\Support\ServiceProvider;
use App\Interface\NotificationRepoInterface;
use App\Repositories\Notifications\NotificationRepo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepoInterface::class,ProductRepo::class);
        $this->app->bind(Login::class, SendLoginEmailNotification::class);
        $this->app->bind(NotificationRepoInterface::class,NotificationRepo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
