<?php

namespace App\Jobs\Project;

use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use App\Models\Project\Service;
use App\Models\Role;
use App\Notifications\Client\QuizIncomplete;
use App\Notifications\Manager\InviteOverdue;
use App\Notifications\Project\Delayed;
use App\Notifications\Project\Remind;
use App\Notifications\Project\WillRemoved;
use App\Notifications\Worker\HasTask;
use App\Notifications\Worker\Progress;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class CheckState
 * @package App\Jobs\Project
 */
class CheckState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Project
     */
    protected $project;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        //todo run every day at 8am - 9am with cron
        //todo use with php artisan queue:listen (instead of work)

        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('state handle start');
        //check is project ready for workers
        if ($this->filling()) {
            return;
        }
        //check the working progress
        $this->progress();
        //check ends_at of project subscription
        $this->subscription();
    }

    /**
     * Check is project still not filled
     */
    public function filling()
    {
        $filling_states = [
            ProjectStates::QUIZ_FILLING,
        ];

        if ($this->project->created_at->diffInDays(now()) > 1 and in_array($this->project->state, $filling_states)) {
            $this->project->client->notify(new Remind($this->project));
            return true;
        }
    }

    /**
     *
     */
    public function subscription()
    {
        if (!$this->project->subscription->ends_at) {
            return;
        }

        if ($this->project->subscription->ends_at->diffInDays(now()) < 2) {
            $this->project->client->notify(new WillRemoved($this->project));
        }

        return;
    }

    /**
     * Check projects state
     */
    public function progress()
    {
        if ($this->project->created_at->diffInDays(now()) > 15 and $this->project->getProgress() < 100) {
            //todo notify workers about progress
            $this->project->workers->each(function (User $user) {
                if ($user->role == Role::ACCOUNT_MANAGER) {
                    $user->notify(new Delayed($this->project));
                } else {
                    $user->notify(new Progress($this->project));
                }
            });
            User::withRole(Role::ADMIN)->get()->each(function (User $user) {
                $user->notify(new Delayed($this->project));
            });
        }

        if ($this->project->created_at->diffInDays(now()) > 3 and $this->project->requireWorkers()) {
            //todo notify workers about progress
            $this->project->workers->each(function (User $user) {
                if ($user->role == Role::ACCOUNT_MANAGER) {
                    $user->notify(new InviteOverdue($this->project));
                }
            });
            User::withRole(Role::ADMIN)->get()->each(function (User $user) {
                $user->notify(new InviteOverdue($this->project));
            });
        }

        if ($this->project->created_at->diffInDays(now()) > 3 and $this->project->state == ProjectStates::QUIZ_FILLING) {
            $this->project->client->notify(
                new QuizIncomplete($this->project)
            );
        }


        if (!$this->project->requireWorkers() and $this->project->created_at->diffInDays(now()) > 1 and
            $this->project->created_at->diffInDays(now()) < 3
        ) {
            $missed_services = collect();
            $project         = Project::first();

            $cycle = $project->cycle;

            if (!$cycle) {
                return;
            }

            $cycle_id = $cycle->id;

            $project->services()
                    ->withType(Service::TYPE_INTEGER)
                    ->required()->get()
                    ->each(function (Service $service) use ($missed_services, $project, $cycle_id) {
                        $require = intval($service->value);
                        $total   = intval($project->getArticleByType($service->name)->byCycle($cycle_id)->count());
                        if ($require > $total) {
                            $missed_services->push($service->display_name);
                        }
                    });

            if ($missed_services->isEmpty()) {
                return;
            }

            //notify workers
            $project->workers->each(function (User $user) use ($project, $missed_services) {
                $user->notify(new HasTask($project, 1, $missed_services->implode(', ')));
            });
        }

        if (!$this->project->requireWorkers() and $this->project->created_at->diffInDays(now()) > 2
        ) {
            $missed_services = collect();
            $project         = Project::first();

            $cycle = $project->cycle;

            if (!$cycle) {
                return;
            }

            $cycle_id = $cycle->id;

            $project->services()
                    ->withType(Service::TYPE_INTEGER)
                    ->required()->get()
                    ->each(function (Service $service) use ($missed_services, $project, $cycle_id) {
                        $require = intval($service->value);
                        $total   = intval($project->getArticleByType($service->name)->byCycle($cycle_id)->count());
                        if ($require > $total) {
                            $missed_services->push($service->display_name);
                        }
                    });

            if ($missed_services->isEmpty()) {
                return;
            }

            //notify workers
            $project->workers->each(function (User $user) use ($project, $missed_services) {
                $user->notify(new HasTask($project, 1, $missed_services->implode(', ')));
            });
        }
    }


}
