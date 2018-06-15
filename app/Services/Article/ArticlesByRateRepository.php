<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.06.18
 * Time: 16:31
 */

namespace App\Services\Article;

use App\Models\Article;
use App\Models\Traits\TimeConstrains;

/**
 * Class ArticlesByRateRepository
 * @package App\Services\Article
 */
class ArticlesByRateRepository
{
    use TimeConstrains;

    /**
     * @param $clientId
     * @param $timeConstrains
     * @param $rate
     * @return mixed
     */
    public function articlesByClient($clientId, $timeConstrains, $rate)
    {
        $query = Article::withRating($rate, ($rate == 3) ? '<=' : '=');
        $query->whereHas('project', function ($query) use ($clientId) {
            $query->where('client_id', $clientId);
        });
        $query = $this->addDateConstrains($query, $timeConstrains);

        return $query->get();
    }

    /**
     * @param $timeConstrains
     * @param $rate
     * @return mixed
     */
    public function allArticles($timeConstrains, $rate)
    {
        $query = Article::withRating($rate, ($rate == 3) ? '<=' : '=');
        $query = $this->addDateConstrains($query, $timeConstrains);

        return $query->get();
    }

    /**
     * @param $userId
     * @param $timeConstrains
     * @param $rate
     * @return mixed
     */
    public function articlesByRelatedProjects($userId, $timeConstrains, $rate)
    {
        $query = Article::from('articles')
            ->join('projects as p', 'p.id', '=', 'articles.project_id')
            ->join('project_worker as pw', 'pw.project_id', '=', 'p.id')
            ->join('users as u', 'pw.user_id', '=', 'u.id')
            ->where('u.id', '=', $userId)
            ->withRating($rate, ($rate == 3) ? '<=' : '=');
        $query = $this->addDateConstrains($query, $timeConstrains);

        return $query->get();
    }
}