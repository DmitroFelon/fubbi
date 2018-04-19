<?php

namespace App\Providers;

use App\Services\User\GlobalNotification;
use Illuminate\Support\ServiceProvider;

class GlobalNotificationProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
       
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('global_notification', function () {
            return new GlobalNotification();
        });
    }
}
