<?php

namespace App\Services\User;

use App\Services\Team\TeamRepository;
use App\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\ChangeEmail;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\ResponseMessage;

/**
 * Class UserManager
 * @package App\Services\User
 */
class UserManager
{
    use ResponseMessage;

    /**
     * @var TeamRepository
     */
    protected $teamRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserManager constructor.
     * @param TeamRepository $teamRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TeamRepository $teamRepository,
        UserRepository $userRepository
    )
    {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @param array $params
     */
    public function create(User $user, array $params)
    {
        $user->fill($params);
        $user->save();
        $this->setRole($user, $params['role']);
        if (! is_null($params['team'])) {
            $team = $this->teamRepository->findTeam($params['team']);
            $this->attachTeam($user, $team);
        }
    }

    /**
     * @param $userId
     * @param $currentUserId
     * @return \App\Services\Response\ResponseDTO
     */
    public function blockOrRestore($userId, $currentUserId)
    {
        if ($currentUserId == $userId) {

            return $this->make('You can\'t block yourself', 'error');
        }
        $user = $this->userRepository->findById($userId);
        if ($user->trashed()) {
            $user->restore();

            return $this->make($user->name . ' has been restored', 'success');
        }
        $user->delete();

        return $this->make($user->name . ' has been blocked', 'success');
    }

    /**
     * @param User $user
     * @param array $params
     * @return \App\Services\Response\ResponseDTO
     */
    public function update(User $user, array $params)
    {
        $user->fill($params);
        $user->setMetaArray($params);
        $user->save();

        return $this->make('Profile has been saved successfully', 'success');
    }

    /**
     * @param User $user
     * @param $email
     * @return \App\Services\Response\ResponseDTO
     */
    public function resetEmailDataCreate(User $user, $email)
    {
        $token = Str::random(60);
        try {
            DB::table('reset_email')->insert([
                'email'      => $email,
                'token'      => $token,
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {

            return $this->make('Such email has been already requested!', 'error');
        }
        $user->notify(new ChangeEmail($token));

        return $this->make('We have e-mailed your email reset link!', 'success');
    }

    /**
     * @param User $user
     * @param $data
     * @param $token
     * @return \App\Services\Response\ResponseDTO
     */
    public function changeEmail(User $user, $data, $token)
    {
        try {
            $this->updateEmail($user, $data->email, $token);
        } catch (\Exception $e) {
            DB::table('reset_email')->where('token', $token)->delete();

            return $this->make('Such email has been already taken!', 'error');
        }

        return $this->make('Your email was successfully changed!', 'success');
    }

    /**
     * @param User $user
     * @param $email
     * @param $token
     */
    protected function updateEmail(User $user, $email, $token)
    {
        User::where('email', '=', $user->email)->update(['email' => $email]);
        DB::table('reset_email')->where('token', $token)->delete();
    }

    /**
     * @param User $user
     * @param array $params
     */
    public function notificationsUpdate(User $user, array $params)
    {
        $disabled_notifications = array_key_exists('disabled_notifications', $params)
            ? $params['disabled_notifications']
            : [];
        $disabled_notifications = collect(array_keys($disabled_notifications))->transform(function ($disabled_notification) {

            return ['name' => $disabled_notification];
        });
        $user->disabled_notifications()
            ->delete();
        $user->disabled_notifications()
            ->createMany($disabled_notifications->toArray());
    }

    /**
     * @param User $user
     * @param array $params
     * @return \App\Services\Response\ResponseDTO
     */
    public function billingUpdate(User $user, array $params)
    {
        if (! is_null($user->stripe_id)) {
            $user->updateCard($params['stripeToken']);

            return $this->make('Card has been updated Successfully.', 'success');
        }
        else {
            $user->createAsStripeCustomer($params['stripeToken']);

            return $this->make('Card has been created Successfully.', 'success');
        }
    }

    /**
     * @param User $user
     * @param $role
     */
    protected function setRole(User $user, $role)
    {
        $user->roles()->attach($role);
        $user->save();
    }

    /**
     * @param User $user
     * @param $team
     */
    protected function attachTeam(User $user, $team)
    {
        $user->teams()->attach($team);
        $user->save();
    }
}