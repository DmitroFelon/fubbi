<?php

namespace App\Providers;

use App\Services\Project\Export;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ProjectExportProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('export', function () {
            return new Export;
        });
    }
}
