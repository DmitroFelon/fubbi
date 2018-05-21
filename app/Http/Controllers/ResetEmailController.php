<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResetEmailRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ChangeEmail;
use Illuminate\Support\Facades\DB;

class ResetEmailController extends Controller
{
    public function sendEmail(ResetEmailRequest $request)
    {
        if (Hash::check($request->input('password'), $request->user()->password))
        {
            try {
                $token = $request->user()->changeEmailRequestData($request->input('email'));
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Such email has been already requested!');
            }
            $request->user()->notify(new ChangeEmail($token));
            return redirect()->back()->with('success', 'We have e-mailed your email reset link!');
        }
        return redirect()->back()->with('error', 'Wrong password!');
    }

    public function reset(Request $request, $token)
    {
        $data = $request->user()->findByResetToken($token);
        if (!is_null($data)) {
            try {
                $request->user()->resetEmail($data->email, $token);
            } catch (\Exception $e) {
                DB::table('reset_email')->where('token', $token)->delete();
                return redirect()->back()->with('error', 'Such email has been already taken!');
            }
            return redirect()->route('settings')->with('success', 'Your email was successfully changed!');
        }
        return redirect()->route('settings')->with('error', 'Something went wrong!');
    }
}
