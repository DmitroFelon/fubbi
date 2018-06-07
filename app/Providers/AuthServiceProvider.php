<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Team;
use App\Policies\ArticlePolicy;
use App\Policies\ChargePolicy;
use App\Policies\PlanPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Stripe\Charge;
use Stripe\Plan;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        User::class    => UserPolicy::class,
        Team::class    => TeamPolicy::class,
        Charge::class  => ChargePolicy::class,
        Plan::class    => PlanPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param GateContract $gate
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        /*
         * project additional policies
         * */
        $gate->define('project.update', ProjectPolicy::class . '@update');
        $gate->define('project.create', ProjectPolicy::class . '@create');
        $gate->define('project.accept-review', ProjectPolicy::class . '@acceptReview');
        $gate->define('project.invite', ProjectPolicy::class . '@attachUsers');
        $gate->define('project.show', ProjectPolicy::class . '@show');
        $gate->define('project.export', ProjectPolicy::class . '@export');

        /*
         * articles additional policies
         * */
        $gate->define('articles.index', ArticlePolicy::class . '@index');
        $gate->define('articles.update', ArticlePolicy::class . '@update');
        $gate->define('articles.create', ArticlePolicy::class . '@create');
        $gate->define('articles.delete', ArticlePolicy::class . '@delete');
        $gate->define('articles.accept', ArticlePolicy::class . '@accept');

        /*
         * User Policy
         * */
        $gate->define('user.show', UserPolicy::class . '@show');
        $gate->define('user.apply_to_project', UserPolicy::class . '@apply_to_project');


    }
}
