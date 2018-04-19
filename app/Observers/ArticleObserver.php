<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 2/12/18
 * Time: 9:44 AM
 */

namespace App\Observers;


use App\Models\Article;
use App\Notifications\Article\Approval;

class ArticleObserver
{
    public function created(Article $article)
    {
        $project = $article->project;

        if (!$project) {
            return;
        }

        $client = $project->client;

        if (!$client) {
            return;
        }

        $client->notify(new Approval($article));
    }

}