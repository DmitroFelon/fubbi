<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Files\Services\ProjectFiles;
use App\Services\Files\Services\ArticleFiles;
use App\Services\Files\Services\InspirationFiles;
use App\Services\Files\Interfaces\ProjectFilesInterface;
use App\Services\Files\Interfaces\ArticleFilesInterface;
use App\Services\Files\Interfaces\InspirationFilesInterface;

class FileServiceProvider extends ServiceProvider
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
        $this->app->bind(ArticleFilesInterface::class, ArticleFiles::class);
        $this->app->bind(ProjectFilesInterface::class, ProjectFiles::class);
        $this->app->bind(InspirationFilesInterface::class, InspirationFiles::class);
    }
}
