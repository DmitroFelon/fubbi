<?php

namespace App\Services\User;

use App\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\ChangeEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Team;
use App\Models\Helpers\ProjectStates;

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

    /**
     * @param User $user
     * @param array $params
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function userCreate(User $user, array $params)
    {
        $user->fill($params);
        $user->password = bcrypt($params['password']);
        $user->save();
        $user->roles()->attach($params['role']);
        $user->save();
        if (array_key_exists('team', $params) and $params['team'] > 0) {
            $team = Team::find($params['team']);
            if ($team) {
                $user->teams()->attach($team);
            }
        }
        Cache::set('temp_password_' . $user->id, $params['password']);
    }

    /**
     * @param User $user
     * @param array $params
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userUpdate(User $user, array $params)
    {
        $user->fill($params);
        if ($params['password']) {
            $user->password = bcrypt($params['password']);
        }
        $user->setMetaArray($params);
        $user->save();
        if (array_key_exists('redirect_to_last_project', $params)) {
            $last_project = $user->projects()->latest('id')->first();
            if ($last_project) {
                return redirect()
                    ->action('Resources\ProjectController@edit', [$last_project, 's' => ProjectStates::QUIZ_FILLING])
                    ->with('success', _i('Please, fill the quiz.'));
            }
        }
        return redirect()->back()->with('success', _i('Profile has been saved successfully'));
    }

    /**
     * @param $userId
     * @param $currentUserId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function blockOrRestore($userId, $currentUserId)
    {
        if ($currentUserId == $userId) {
            return redirect()->back()->with('error', "You can't block yourself");
        }
        $user = User::withTrashed()->find($userId);
        if ($user->trashed()) {
            $user->restore();
            return redirect()->back()->with('success', $user->name . ' has been restored');
        }
        $user->delete();
        return redirect()->back()->with('error', $user->name . ' has been blocked');
    }
}