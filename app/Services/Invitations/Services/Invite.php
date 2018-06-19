<?php

namespace App\Services\Invitations\Services;

use App\Services\Invitations\Interfaces\InviteInterface;
use App\Services\Response\ResponseDTO;
use App\User;
use App\Models\Traits\ResponseMessage;

/**
 * Class Invite
 * @package App\Services\Invitations\Services
 */
abstract class Invite implements InviteInterface
{
    use ResponseMessage;

    /**
     * @param $inviteFrom
     * @param $userId
     * @return mixed
     */
    abstract protected function attachUser($inviteFrom, $userId);

    /**
     * @param User $user
     * @param $inviteFromId
     * @return mixed
     */
    abstract protected function getInvite(User $user, $inviteFromId);

    /**
     * @param User $user
     * @param $inviteFrom
     * @return ResponseDTO
     */
    public function accept(User $user, $inviteFrom): ResponseDTO
    {
        $invite = $this->getInvite($user, $inviteFrom->id);

        return $this->acceptInvite($invite)
            ? $this->attachUser($inviteFrom, $user->id)
            : $this->make('The invitation hasn\'t been found.', 'error');
    }

    /**
     * @param User $user
     * @param $inviteFrom
     * @return ResponseDTO
     */
    public function decline(User $user, $inviteFrom): ResponseDTO
    {
        $invite = $this->getInvite($user, $inviteFrom->id);

        return $this->declineInvite($invite)
            ? $this->make('The invitation has been successfully declined.', 'info')
            : $this->make('The invitation hasn\'t been found.', 'error');
    }

    /**
     * @param $invite
     * @return bool
     */
    protected function acceptInvite($invite)
    {
        if ($invite) {
            $invite->accept();

            return true;
        }

        return false;
    }

    /**
     * @param $invite
     * @return bool
     */
    protected function declineInvite($invite)
    {
        if ($invite) {
            $invite->decline();

            return true;
        }

        return false;
    }
}