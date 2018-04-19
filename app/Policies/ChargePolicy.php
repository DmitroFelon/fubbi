<?php

namespace App\Policies;

use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChargePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        $allow = [
            Role::ADMIN
        ];

        return in_array($user->role, $allow);
    }
}
