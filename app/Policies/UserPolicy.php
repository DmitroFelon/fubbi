<?php

namespace App\Policies;

use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

//  TODO implement account create/update policy
/**
 * Class UserPolicy
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        $allow = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER,
        ];

        return in_array($user->role, $allow);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param $id
     * @return mixed
     * @internal param User $model
     */
    public function show(User $user, $id)
    {
        $model = User::withTrashed()->findOrFail($id);
        $skip = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        if ($user->id == $model->id) {
            return true;
        }

        $result = $user->projects()->whereHas('workers', function ($query) use ($model) {
            $query->where('id', $model->id);
        })->get();

        return ($result->isNotEmpty())
            ? true
            : false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {

        $skip = [
            Role::ADMIN,
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        return ($user->id == $model->id);

    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \App\User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {

        $skip = [
            Role::ADMIN,
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        return ($user->id == $model->id);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        $allow = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER
        ];

        return in_array($user->role, $allow);

    }

    public function apply_to_project(User $user, Project $model)
    {
        $invite = $user->getInviteToProject($model->id);

        return ($invite) ? true : false;
    }
}
