<?php

namespace App\Providers;

use App\Services\ProjectParticipants\Interfaces\ParticipantTeamInterface;
use App\Services\ProjectParticipants\Interfaces\ParticipantUserInterface;
use App\Services\ProjectParticipants\Services\ParticipantTeam;
use App\Services\ProjectParticipants\Services\ParticipantUser;
use Illuminate\Support\ServiceProvider;

class ProjectWorkersServiceProvider extends ServiceProvider
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
        $this->app->bind(ParticipantTeamInterface::class, ParticipantTeam::class);
        $this->app->bind(ParticipantUserInterface::class, ParticipantUser::class);
    }
}
