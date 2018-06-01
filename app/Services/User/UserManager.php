<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 16:03
 */

namespace App\Services\User;

use App\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\ChangeEmail;
use Illuminate\Support\Facades\DB;

/**
 * Class UserManager
 * @package App\Services\User
 */
class UserManager
{
    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetEmailData(User $user)
    {
        try {
            $token = Str::random(60);
            DB::table('reset_email')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Such email has been already requested!');
        }
        $user->notify(new ChangeEmail($token));
    }

    /**
     * @param User $user
     * @param $data
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetEmail(User $user, $data, $token)
    {
        try {
            $user->resetEmail($data->email, $token);
        } catch (\Exception $e) {
            DB::table('reset_email')->where('token', $token)->delete();
            return redirect()->back()->with('error', 'Such email has been already taken!');
        }
    }

    /**
     * @param User $user
     * @param array $params
     */
    public function notifications(User $user, array $params)
    {
        $disabled_notifications = array_key_exists('disabled_notifications', $params)
            ? $params['disabled_notifications']
            : [];
        $disabled_notifications = collect(array_keys($disabled_notifications))->transform(function ($disabled_notification) {
            return ['name' => $disabled_notification];
        });
        $user->disabled_notifications()->delete();
        $user->disabled_notifications()->createMany(
            $disabled_notifications->toArray()
        );
    }

    /**
     * @param User $user
     * @param array $params
     * @return \Illuminate\Http\RedirectResponse
     */
    public function billing(User $user, array $params)
    {
        try {
            if (!is_null($user->stripe_id)) {
                try {
                    $user->updateCard($params['stripeToken']);
                } catch (\Exception $e) {
                    return back()->with('error', 'Something wrong happened while billing info updating, please try later.');
                }
                return redirect()->back()->with(
                    'success', _i('Card has been updated Successfully')
                );
            }
            else {
                $user->createAsStripeCustomer($params['stripeToken']);
                return redirect()->back()->with(
                    'success', _i('Card has been created Successfully')
                );
            }
        } catch (\Stripe\Error\Card $e) {
            $body  = $e->getJsonBody();
            $error = $body['error'];
            return redirect()->back()->with(
                'error', $error['message']
            );
        }
    }
}