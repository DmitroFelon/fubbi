<?php

namespace App\Http\Controllers;

use App\Models\Helpers\NotificationTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 */
class SettingsController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $user = Auth::user();

        $data = [
            'notifications_checkboxes' => NotificationTypes::get($user->role),
            'user'                     => $user,
        ];

        return view('entity.user.settings', $data);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $user = Auth::user();

        $disabled_notifications = ($request->has('disabled_notifications'))
            ? $request->input('disabled_notifications')
            : [];

        $disabled_notifications = collect(array_keys($disabled_notifications))->transform(function ($disabled_notification) {
            return ['name' => $disabled_notification];
        });

        $user->disabled_notifications()->delete();

        $user->disabled_notifications()->createMany(
            $disabled_notifications->toArray()
        );

        return redirect()->back()->with(
            'success', _i('Notification options have beed saved')
        );

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function billing(Request $request)
    {
        $user = Auth::user();

        if ($request->input('stripeToken')) {
            try {
                $user->updateCard($request->input('stripeToken'));

                return redirect()->back()->with(
                    'success', _i('Card has been updated Successfully')
                );

            } catch (\Stripe\Error\Card $e) {
                $body  = $e->getJsonBody();
                $error = $body['error'];
                return redirect()->back()->with(
                    'error', $error['message']
                );
            }
        }


        return back()->with('error', 'Something wrong happened while billing info updating, please try later.');
    }
}
