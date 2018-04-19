<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Project;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectArticlePolicy
{
    use HandlesAuthorization;

    public function index(User $user, Project $relation, Article $model)
    {
        return false;
    }


    public function create()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
