<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 01/12/17
 * Time: 08:22
 */

namespace App\Observers;

use App\Models\Invite;

class InviteObserver
{
    public function created(Invite $invite)
    {

        $notification = $invite->invitable->getInvitableNotification();
        $invite->user->notify(new $notification($invite));
    }

    public function accepted(Invite $invite)
    {
    }

    public function rejected(Invite $invite)
    {
    }
}