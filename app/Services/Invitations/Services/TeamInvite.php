<?php

namespace App\Services\Invitations\Services;

use App\Services\Invitations\Interfaces\TeamInviteInterface;
use App\User;

/**
 * Class TeamInvite
 * @package App\Services\Invitations\Services
 */
class TeamInvite extends Invite implements TeamInviteInterface
{
    /**
     * @param $inviteFrom
     * @param $userId
     * @return \App\Services\Invitations\ResponseDTO|mixed
     */
    protected function attachUser($inviteFrom, $userId)
    {
        $inviteFrom->users()->attach($userId);

        return $this->makeResponseMessage('The invitation has benn successfully accepted!', 'success');
    }

    /**
     * @param User $user
     * @param $inviteFromId
     * @return mixed
     */
    protected function getInvite(User $user, $inviteFromId)
    {
        return $user->getInviteToTeam($inviteFromId);
    }
}