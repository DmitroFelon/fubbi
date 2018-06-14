<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.06.18
 * Time: 12:22
 */

namespace App\Services\Article;

use App\Models\Article;

/**
 * Class OverdueArticleRepository
 * @package App\Services\Article
 */
class OverdueArticleRepository
{
    /**
     * @param $clientId
     * @param $overdue
     * @return Article[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function articlesByClient($clientId, $overdue)
    {
        $query = Article::new();
        $query->whereHas('project', function ($query) use ($clientId) {
            $query->where('client_id', $clientId);
        });
        $query->overdue($overdue);

        return $query->get();
    }

    /**
     * @param $overdue
     * @return Article[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allArticles($overdue)
    {
        $query = Article::new();
        $query->overdue($overdue);

        return $query->get();
    }

    /**
     * @param $userId
     * @param $overdue
     * @return Article[]|\Illuminate\Database\Eloquent\Collection
     */
    public function articlesByRelatedProjects($userId, $overdue)
    {
        $query = Article::from('articles')
            ->join('projects as p', 'p.id', '=', 'articles.project_id')
            ->join('project_worker as pw', 'pw.project_id', '=', 'p.id')
            ->join('users as u', 'pw.user_id', '=', 'u.id')
            ->where('u.id', '=', $userId);
        $query->overdue($overdue);

        return $query->get();
    }


}