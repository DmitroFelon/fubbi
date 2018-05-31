<?php

namespace App\Services\Article;

use App\Models\Role;
use App\Models\Article;
use App\User;
use Illuminate\Http\Request;

/**
 * Class ArticleRepository
 * @package App\Services\Article
 */
class ArticleRepository
{
    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function articlesByRole(User $user)
    {
        if ($user->role == Role::ADMIN) {
            $articles_query = Article::query();
        } elseif ($user->role == Role::CLIENT) {
            $articles_query = $user->relatedClientArticles();
        } else {
            $articles_query = $user->relatedClientArticles();
        }
        return $articles_query;
    }

    /**
     * @param Request $request
     * @param $articles_query
     * @return mixed
     */
    public function search(Request $request, $articles_query)
    {
        if ($request->has('type') and $request->input('type') != '') {
            $articles_query->where('type', $request->input('type'));
        }
        if ($request->has('status') and $request->input('status') != '') {
            $articles_query->where('accepted', intval($request->input('status')));
        }
        if ($request->has('active') and $request->input('active') != '') {
            $articles_query->where('active', true);
        }
        return $articles_query;
    }
}
