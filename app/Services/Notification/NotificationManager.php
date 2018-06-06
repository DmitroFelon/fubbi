<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.06.18
 * Time: 9:10
 */

namespace App\Services\Notification;


/**
 * Class NotificationManager
 * @package App\Services\Notification
 */
class NotificationManager
{
    /**
     * @param $notifications
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function read($notifications)
    {
        try {
            $notifications->markAsRead();
        } catch (\Error $e) {
            return redirect('notification');
        }
    }
}