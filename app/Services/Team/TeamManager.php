<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.18
 * Time: 16:44
 */

namespace App\Services\Team;

use App\User;
use App\Models\Team;

/**
 * Class TeamManager
 * @package App\Services\Team
 */
class TeamManager
{
    /**
     * @param Team $team
     * @param array $params
     */
    public function updateTeam(Team $team, array $params)
    {
        $team->update($params);
        if (array_key_exists('users', $params)) {
            $this->inviteUsers($team, $params);
        }
    }

    /**
     * @param Team $team
     * @param array $params
     */
    public function createTeam(Team $team, array $params)
    {
        $team->fill($params);
        $team->save();
        if (array_key_exists('users', $params)) {
            $this->inviteUsers($team, $params);
        }
    }

    /**
     * @param Team $team
     * @param array $params
     */
    protected function inviteUsers(Team $team, array $params)
    {
        $ids   = array_keys($params['users']);
        $users = User::whereIn('id', $ids)->get();
        $users->each(function (User $user) use ($team) {
            $user->inviteTo($team);
        });
    }
}