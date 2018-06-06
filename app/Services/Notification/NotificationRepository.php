<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.06.18
 * Time: 8:42
 */

namespace App\Services\Notification;

use App\User;
use Musonza\Chat\Notifications\MessageSent;

/**
 * Class NotificationRepository
 * @package App\Services\Notification
 */
class NotificationRepository
{
    /**
     * @param User $user
     * @return array
     */
    public function allUserNotifications(User $user)
    {
        $page_notifications = $user
            ->notifications()
            ->where('type', '!=', MessageSent::class)
            ->paginate(10);
        $has_unread_notifications = $user
            ->unreadNotifications()
            ->where('type', '!=', MessageSent::class)
            ->get()->isNotEmpty();
        $data = [
            'page_notifications'       => $page_notifications,
            'has_unread_notifications' => $has_unread_notifications,
        ];
        return $data;
    }
}