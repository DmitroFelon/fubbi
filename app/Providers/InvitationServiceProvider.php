<?php

namespace App\Providers;

use App\Services\Invitations\Services\TeamInvite;
use App\Services\Invitations\Services\ProjectInvite;
use App\Services\Invitations\Interfaces\TeamInviteInterface;
use App\Services\Invitations\Interfaces\ProjectInviteInterface;
use Illuminate\Support\ServiceProvider;

class InvitationServiceProvider extends ServiceProvider
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
        $this->app->bind(TeamInviteInterface::class, TeamInvite::class);
        $this->app->bind(ProjectInviteInterface::class, ProjectInvite::class);
    }
}
