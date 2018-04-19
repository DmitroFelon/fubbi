<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Musonza\Chat\Notifications\MessageSent;

/**
 * Class NotificationController
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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

        return view('entity.notification.index', $data);
    }

    public function show($id)
    {
        $notification = Auth::user()->unreadNotifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['link']);
    }

    /**
     * @param null $id
     * @return mixed
     */
    public function read($id = null)
    {
        if (!is_null($id)) {
            $notification = Auth::user()->notifications()->where('id', $id)->first();
            if (!is_null($notification)) {
                $notification->markAsRead();
            }
        } else {
            Auth::user()->unreadNotifications->markAsRead();
        }

        return redirect('notification');
    }
}
