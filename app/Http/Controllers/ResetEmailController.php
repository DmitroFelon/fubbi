<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResetEmailRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ChangeEmail;

class ResetEmailController extends Controller
{
    public function sendEmail(ResetEmailRequest $request)
    {
        if (Hash::check($request->input('password'), $request->user()->password))
        {
            $token = $request->user()->changeEmailRequestData($request->input('email'));
            $request->user()->notify(new ChangeEmail($token));
            return redirect()->back()->with('success', 'We have e-mailed your email reset link!');
        }
        return redirect()->back()->with('error', 'Wrong password!');
    }

    public function reset(Request $request, $token)
    {
        $data = $request->user()->findByResetToken($token);
        if (!is_null($data)) {
            $request->user()->resetEmail($data->email, $token);
            return redirect()->route('settings')->with('success', 'Your email was successfully changed!');
        }
        return redirect()->route('settings')->with('error', 'Something went wrong!');
    }
}
