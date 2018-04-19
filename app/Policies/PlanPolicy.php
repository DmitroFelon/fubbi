<?php

namespace App\Policies;

use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Stripe\Plan;

class PlanPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        return true;
    }

    public function index(User $user)
    {
        $allow = [
            Role::ADMIN
        ];


        return (in_array($user->role, $allow));
    }

    public function show(User $user)
    {
        $allow = [
            Role::ADMIN
        ];


        return (in_array($user->role, $allow));
    }

    public function update(User $user)
    {
        $allow = [
            Role::ADMIN
        ];


        return (in_array($user->role, $allow));
    }

}
