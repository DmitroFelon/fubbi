<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

//TODO implement actions authorisation
/**
 * Class ProjectPolicy
 * @package App\Policies
 */
class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function index(User $user)
    {
        return true;
    }

    public function show(User $user, Project $model)
    {
        $skip = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        if ($model->client_id === $user->id) {
            return true;
        }

        $result = $model->workers->find($user->id);

        if ($result) {
            return true;
        }

        if ($user->teamProjects()->get('id', $model->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {

        return false;

        $allow = [
            Role::ADMIN,
            Role::CLIENT,
        ];

        return (in_array($user->role, $allow));
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User $user
     * @param  \App\Models\Project $model
     * @return mixed
     */
    public function update(User $user, Project $model)
    {
        $skip = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER,
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        if ($model->client_id == $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User $user
     * @param  \App\Models\Project $model
     * @return mixed
     */
    public function delete(User $user, Project $model)
    {
        $skip = [
            Role::ADMIN
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        return ($model->client_id == $user->id);
    }

    /**
     * @param User $user
     * @param Project $model
     * @return bool
     */
    public function acceptReview(User $user, Project $model)
    {
        $skip = [
            Role::ADMIN
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        $manager = $model->workers()->withRole(Role::ACCOUNT_MANAGER)->first(['id']);

        if ($manager and $user->id == $manager->id) {
            return true;
        }
    }

    public function attachUsers(User $user, Project $model)
    {
        return $this->acceptReview($user, $model);
    }


}
