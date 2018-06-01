<?php

namespace App\Http\Controllers;

use App\Models\Helpers\NotificationTypes;
use App\Services\User\UserManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $data = [
            'notifications_checkboxes' => NotificationTypes::get(Auth::user()->role),
            'user'                     => Auth::user(),
        ];
        return view('entity.user.settings', $data);
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, UserManager $userManager)
    {
        $userManager->notifications($request->user(), $request->input());
        return redirect()->back()->with('success', _i('Notification options have been saved'));
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function billing(Request $request, UserManager $userManager)
    {
        if ($request->input('stripeToken')) {
            $userManager->billing($request->user(), $request->input());
        }
        return back()->with('error', 'Something wrong happened while billing info updating, please try later.');
    }
}
