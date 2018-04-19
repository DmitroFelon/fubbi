<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/26/18
 * Time: 2:01 PM
 */

namespace App\Models\Traits\User;


use App\Models\DisabledNotifications;

/**
 * Class hasNotificationSettings
 * @package App\Models\Traits\User
 */
trait hasNotificationSettings
{
    /**
     * @return mixed
     */
    public function disabled_notifications()
    {
        return $this->hasMany(DisabledNotifications::class);
    }

   
}