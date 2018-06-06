<?php

namespace App\Http\Controllers;

use App\Services\Notification\NotificationManager;
use App\Services\Notification\NotificationRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class NotificationController
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    /**
     * @param NotificationRepository $notificationRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NotificationRepository $notificationRepository)
    {
        $data = $notificationRepository->allUserNotifications(Auth::user());
        return view('entity.notification.index', $data);
    }

    /**
     * @param $id
     * @param NotificationManager $notificationManager
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show($id, NotificationManager $notificationManager)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notificationManager->read($notification);
        return redirect($notification->data['link']);
    }

    /**
     * @param $id
     * @param NotificationManager $notificationManager
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function read($id, NotificationManager $notificationManager)
    {
        $notificationManager->read(Auth::user()->notifications()->findOrFail($id));
        return redirect('notification');
    }

    /**
     * @param NotificationManager $notificationManager
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function readAll(NotificationManager $notificationManager)
    {
        $notificationManager->read(Auth::user()->unreadNotifications);
        return redirect('notification');
    }
}
