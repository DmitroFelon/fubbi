<?php

namespace App\Jobs\Article;

use App\Models\Article;
use App\Models\Role;
use App\Notifications\Manager\ArticleLowRating;
use App\Notifications\Manager\ArticleOverdue;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->check();
    }

    /**
     * Check articles state
     */
    public function check()
    {
        Article::overdue(3)->get()->each(function (Article $article) {
            $project = $article->project;

            if (!$project) {
                return;
            }

            User::withRole(Role::ADMIN)->get()->each(function (User $user) use ($article) {
                $user->notify(new ArticleOverdue($article));
            });

            $manager = $project->workers()->withRole(Role::ACCOUNT_MANAGER)->first();

            if (!$manager) {
                return;
            }

            $manager->notify(new ArticleOverdue($article));
        });

        Article::all()->each(function (Article $article) {
            $project = $article->project;
            if (!$project) {
                return;
            }

            User::withRole(Role::ADMIN)->get()->each(function (User $user) use ($article) {
                $user->notify(new ArticleLowRating($article));
            });

            $manager = $project->workers()->withRole(Role::ACCOUNT_MANAGER)->first();
            if (!$manager) {
                return;
            }
            if ($article->avg_rating < 3.5) {
                $manager->notify(new ArticleLowRating($article));
            }
        });
    }
}
