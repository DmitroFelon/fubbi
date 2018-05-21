<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\ResetEmailRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\EmailChange;
use App\Notifications\ChangeEmail;

class ResetEmailController extends Controller
{
    public function sendEmail(ResetEmailRequest $request)
    {
        $user = User::find(Auth::id());
        if (Hash::check($request->input('password'), $user->password))
        {
            $token = Str::random(60);
            DB::table('reset_email')->insert([
                'current_email' => Auth::user()->email,
                'new_email' => $request->input('new_email'),
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $user->notify(new ChangeEmail($token));
            return redirect()->back()->with('success', 'We have e-mailed your email reset link!');
        }
        return redirect()->back()->with('error', 'Wrong password!');

    }

    public function reset($token)
    {
        $data = DB::table('reset_email')->where('token', $token)->first();
        if (!is_null($data)) {
            User::where('email', '=', $data->current_email)->update(['email' => $data->new_email]);
            DB::table('reset_email')->where('token', $token)->delete();
            return redirect()->route('settings')->with('success', 'Your email was successfully changed!');
        }
        return redirect()->route('settings')->with('error', 'Something went wrong!');
    }
}
