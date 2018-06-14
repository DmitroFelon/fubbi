<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.06.18
 * Time: 14:16
 */

namespace App\Services\Article;

use App\Models\Article;
use App\Models\Traits\TimeConstrains;

/**
 * Class DeclinedArticleRepository
 * @package App\Services\Article
 */
class DeclinedArticleRepository
{
    use TimeConstrains;

    /**
     * @param $clientId
     * @param $timeConstrains
     * @return mixed
     */
    public function articlesByClient($clientId, $timeConstrains)
    {
        $query = Article::declined();
        $query->whereHas('project', function ($query) use ($clientId) {
            $query->where('client_id', $clientId);
        });
        $query = $this->addDateConstrains($query, $timeConstrains);

        return $query->get();
    }

    /**
     * @param $timeConstrains
     * @return mixed
     */
    public function allArticles($timeConstrains)
    {
        $query = Article::declined();
        $query = $this->addDateConstrains($query, $timeConstrains);

        return $query->get();
    }

    /**
     * @param $userId
     * @param $timeConstrains
     * @return mixed
     */
    public function articlesByRelatedProjects($userId, $timeConstrains)
    {
        $query = Article::from('articles')
            ->join('projects as p', 'p.id', '=', 'articles.project_id')
            ->join('project_worker as pw', 'pw.project_id', '=', 'p.id')
            ->join('users as u', 'pw.user_id', '=', 'u.id')
            ->where('u.id', '=', $userId)
            ->declined();
        $query = $this->addDateConstrains($query, $timeConstrains);

        return $query->get();
    }
}