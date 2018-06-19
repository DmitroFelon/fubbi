<?php

namespace App\Http\Controllers\Auth;

use App\Services\User\UserManager;
use App\Services\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ResetEmailRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

/**
 * Class ResetEmailController
 * @package App\Http\Controllers\Auth
 */
class ResetEmailController extends Controller
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ResetEmailController constructor.
     * @param UserManager $userManager
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserManager $userManager,
        UserRepository $userRepository
    )
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param ResetEmailRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEmail(ResetEmailRequest $request)
    {
        if (Hash::check($request->input('password'), $request->user()->password))
        {
            $response = $this->userManager->resetEmailDataCreate($request->user(), $request->input('email'));

            return redirect()->back()->with($response->status, $response->message);
        }

        return redirect()->back()->with('error', 'Wrong password!');
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request, $token)
    {
        $data = $this->userRepository->findByResetToken($token);
        if (! is_null($data)) {
            $response = $this->userManager->changeEmail($request->user(), $data, $token);

            return redirect()->route('settings')->with($response->status, $response->message);
        }

        return redirect()->route('settings')->with('error', 'Something went wrong!');
    }
}
