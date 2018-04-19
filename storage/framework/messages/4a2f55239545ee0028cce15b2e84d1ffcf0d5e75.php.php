<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class NotificationController
 *
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
	public function index()
	{
		$user =  Auth::user();

		$data = [
			'page_notifications'       => $user->unreadNotifications->concat($user->readNotifications)->all(),
			'has_unread_notifications' => $user->unreadNotifications->isNotEmpty(),
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
		if (! is_null($id)) {
			$notification = Auth::user()->notifications()->where('id', $id)->first();
			if (! is_null($notification)) {
				$notification->markAsRead();
			}
		} else {
			Auth::user()->unreadNotifications->markAsRead();
		}

		return redirect('notification');
	}
}
