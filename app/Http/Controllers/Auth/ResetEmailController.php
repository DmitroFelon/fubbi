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
     * @param ResetEmailRequest $request
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEmail(ResetEmailRequest $request, UserManager $userManager)
    {
        if (Hash::check($request->input('password'), $request->user()->password))
        {
            $userManager->resetEmailData($request->user());
            return redirect()->back()->with('success', 'We have e-mailed your email reset link!');
        }
        return redirect()->back()->with('error', 'Wrong password!');
    }

    /**
     * @param Request $request
     * @param $token
     * @param UserRepository $userRepository
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request, $token, UserRepository $userRepository, UserManager $userManager)
    {
        $data = $userRepository->findByResetToken($token);
        if (!is_null($data)) {
            $userManager->resetEmail($request->user(), $data, $token);
            return redirect()->route('settings')->with('success', 'Your email was successfully changed!');
        }
        return redirect()->route('settings')->with('error', 'Something went wrong!');
    }
}
