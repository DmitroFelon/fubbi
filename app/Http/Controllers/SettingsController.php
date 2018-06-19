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
     * @var UserManager
     */
    protected $userManager;

    /**
     * SettingsController constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $this->userManager->notificationsUpdate($request->user(), $request->input());

        return redirect()->back()->with('success', _i('Notification options have been saved'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function billing(Request $request)
    {
        $response = $this->userManager->billingUpdate($request->user(), $request->input());

        return back()->with($response->status, $response->message);
    }
}
