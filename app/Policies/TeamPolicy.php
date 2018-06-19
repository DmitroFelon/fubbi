<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;

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
        return $user->role == Role::CLIENT
            ? false
            : true;
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function show(User $user, Team $team)
    {
        if ($user->role == Role::ADMIN) {

            return true;
        }
        if ($user->teams->find($team->id)){

            return true;
        }
        if ($user->getInviteToTeam($team->id)) {

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role == Role::ADMIN
            ? true
            : false;
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function delete(User $user, Team $team)
    {
        return $user->role == Role::ADMIN && $team->owner_id == $user->id
            ? true
            : false;
    }

}
