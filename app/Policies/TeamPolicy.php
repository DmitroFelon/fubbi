<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

/**
 * Class TeamPolicy
 * @package App\Policies
 */
class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user)
    {
        $deny = [
            \App\Models\Role::CLIENT
        ];

        return !in_array($user->role, $deny);
    }

    /**
     * @param User $user
     * @param Team $model
     * @return bool
     */
    public function show(User $user, Team $model)
    {

        $skip = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }


        if($user->teams->find($model->id)){
            return true;
        }

        if ($user->getInviteToTeam($model->id)) {
            return true;
        }


        return false;
    }

    /**
     * @param User $user
     * @param Team $model
     * @return bool
     */
    public function create(User $user)
    {
        $deny = [
            \App\Models\Role::CLIENT
        ];

        return !in_array($user->role, $deny);
    }

    /**
     * @param User $user
     * @param Team $model
     * @return bool
     */
    public function delete(User $user, Team $model)
    {
        $skip = [
            Role::ADMIN
        ];

        if (in_array($user->role, $skip)) {
            return true;
        }

        return ($model->owner_id == $user->id);
    }

}
