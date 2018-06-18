<?php

namespace App\Policies;

use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;

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
     * @param User $targetUser
     * @return bool
     */
    public function show(User $targetUser)
    {
        $currentUser = Auth::user();
        $allow = [
            Role::ADMIN,
            Role::ACCOUNT_MANAGER
        ];
        if (in_array($currentUser->role, $allow)) {

            return true;
        }
        if ($currentUser->id == $targetUser->id) {

            return true;
        }

        return false;
    }

    /**
     * @param User $targetUser
     * @return bool
     */
    public function update(User $targetUser)
    {
        if (Auth::user()->id == $targetUser->id) {

            return true;
        }

        return false;
    }
}
