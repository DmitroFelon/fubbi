<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.18
 * Time: 16:44
 */

namespace App\Services\Team;

use App\Services\User\UserRepository;
use App\User;
use App\Models\Team;

/**
 * Class TeamManager
 * @package App\Services\Team
 */
class TeamManager
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * TeamManager constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Team $team
     * @param array $params
     */
    public function update(Team $team, array $params)
    {
        $team->update($params);
        if (array_key_exists('users', $params)) {
            $users = $this->getUsers($params['users']);
            $this->inviteUsers($team, $users);
        }
    }

    /**
     * @param Team $team
     * @param array $params
     */
    public function create(Team $team, array $params)
    {
        $team->fill($params);
        $team->save();
        if (array_key_exists('users', $params)) {
            $users = $this->getUsers($params['users']);
            $this->inviteUsers($team, $users);
        }
    }

    /**
     * @param Team $team
     * @throws \Exception
     */
    public function delete(Team $team)
    {
        $team->delete();
    }

    /**
     * @param array $params
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getUsers(array $params)
    {
        $ids   = array_keys($params);
        $users = $this->userRepository->findByIds($ids);

        return $users;
    }

    /**
     * @param Team $team
     * @param $users
     */
    protected function inviteUsers(Team $team, $users)
    {
        $users->each(function (User $user) use ($team) {
            $user->inviteTo($team);
        });
    }
}