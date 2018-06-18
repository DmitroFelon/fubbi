<?php

namespace App\Services\Invitations\Interfaces;

use App\Services\Invitations\ResponseDTO;
use App\User;

/**
 * Interface InviteInterface
 * @package App\Services\Interfaces
 */
interface InviteInterface
{
    /**
     * @param User $user
     * @param $inviteFrom
     * @return ResponseDTO
     */
    public function accept(User $user, $inviteFrom): ResponseDTO;

    /**
     * @param User $user
     * @param $inviteFrom
     * @return ResponseDTO
     */
    public function decline(User $user, $inviteFrom): ResponseDTO;
}