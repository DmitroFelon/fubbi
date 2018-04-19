<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Project;
use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ArticlePolicy
 * @package App\Policies
 */
class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {

        $strict_abilities = [
            'articles.accept'
        ];

        $skip = [
            Role::ADMIN,
        ];

        if (!in_array($ability, $strict_abilities)) {
            if (in_array($user->role, $skip)) {
                return true;
            }
        }
    }

    /**
     * @param User $user
     * @param Project $model
     * @return bool
     */
    public function index(User $user, Project $model)
    {

        if($user->teamProjects()->get('id', $model->id)){
            return true;
        }

        return ($user->projects()->find($model->id)) ? true : false;
    }

    /**
     * @param User $user
     * @param Project $model
     * @param Article $article
     * @return bool
     */
    public function update(User $user, Project $model, Article $article)
    {
        return ($user->projects()->find($model->id)) ? true : false;
    }

    /**
     * @param User $user
     * @param Project $model
     * @return bool
     */
    public function create(User $user, Project $model)
    {

        $deny = [
            Role::CLIENT,
        ];

        if (in_array($user->role, $deny)) {
            return false;
        }

        if($user->teamProjects()->get('id', $model->id)){
            return true;
        }

        return ($user->projects()->find($model->id)) ? true : false;
    }

    /**
     * @param User $user
     * @param Project $model
     * @param Article $article
     * @return bool
     */
    public function delete(User $user, Project $model, Article $article)
    {

        if($user->teamProjects()->get('id', $model->id)){
            return true;
        }

        return ($user->projects()->find($model->id)) ? true : false;
    }

    /**
     * @param User $user
     * @param Project $model
     * @param Article $article
     * @return bool
     */
    public function accept(User $user, Project $model, Article $article)
    {
        $res = ($model->client_id === $user->id);

        return $res;
    }
}
