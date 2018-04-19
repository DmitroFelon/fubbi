<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 20/11/17
 * Time: 12:45
 */

namespace App\Observers;

use App\Models\Role;
use App\Notifications\Client\Registered;
use App\Notifications\RegistrationConfirmation;
use App\User;
use Illuminate\Support\Facades\Request;


class UserObserver
{
    public function created(User $user)
    {
        $user->notify(new RegistrationConfirmation($user));
        $admins = User::withRole(Role::ADMIN)->get();
        $admins->each(function (User $admin) use ($user) {
            //check if this notification is not disabled
            if ($admin->disabled_notifications()->where('name', \App\Notifications\Client\Registered::class)->get()
                      ->isEmpty()
            ) {
                $admin->notify(new Registered($user));
            }
        });
    }

}