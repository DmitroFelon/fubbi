<?php

namespace App\Services\Invitations\Services;

use App\Services\Invitations\Interfaces\InviteInterface;
use App\Services\Invitations\ResponseDTO;
use App\User;

/**
 * Class Invite
 * @package App\Services\Invitations\Services
 */
abstract class Invite implements InviteInterface
{
    /**
     * @var ResponseDTO
     */
    protected $response;

    /**
     * Invite constructor.
     */
    public function __construct()
    {
        $this->response = new ResponseDTO();
    }

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
            : $this->makeResponseMessage('The invitation hasn\'t been found.', 'error');
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
            ? $this->makeResponseMessage('The invitation has been successfully declined.', 'info')
            : $this->makeResponseMessage('The invitation hasn\'t been found.', 'error');
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

    /**
     * @param string $message
     * @param string $status
     * @return ResponseDTO
     */
    protected function makeResponseMessage(string $message, string $status)
    {
        $this->response->message = $message;
        $this->response->status = $status;

        return $this->response;
    }
}