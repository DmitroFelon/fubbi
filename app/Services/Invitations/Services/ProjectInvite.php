<?php

namespace App\Services\Invitations\Services;

use App\Services\Invitations\Interfaces\ProjectInviteInterface;
use App\User;

/**
 * Class ProjectInvite
 * @package App\Services\Invitations\Services
 */
class ProjectInvite extends Invite implements ProjectInviteInterface
{
    /**
     * @param $inviteFrom
     * @param $userId
     * @return \App\Services\Response\ResponseDTO|mixed
     */
    protected function attachUser($inviteFrom, $userId)
    {
        $inviteFrom->attachWorker($userId);

        return $this->make('The invitation has been successfully accepted!', 'success');
    }

    /**
     * @param User $user
     * @param $inviteFromId
     * @return mixed
     */
    protected function getInvite(User $user, $inviteFromId)
    {
        return $user->getInviteToProject($inviteFromId);
    }
}