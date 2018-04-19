<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 01/12/17
 * Time: 08:43
 */

namespace App\Models\Interfaces;

/**
 * Interface Invitable
 *
 * Used in models which should be invitable
 *
 * @package App\Models\Interfaces
 */
interface Invitable
{
    public function getInvitableName();

    public function getInvitableId();

    public function getInvitableUrl();

    public function getInvitableNotification();
}