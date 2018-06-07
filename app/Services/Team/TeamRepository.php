<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.18
 * Time: 17:05
 */

namespace App\Services\Team;

use App\Models\Team;
use App\User;

/**
 * Class TeamRepository
 * @package App\Services\Team
 */
class TeamRepository
{
    /**
     * @param User $user
     * @return Team[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function teams(User $user)
    {
        switch ($user->role) {
            case 'admin':
                $teams = Team::with('users')->get();
                break;
            case 'client':
                $teams = $user->teams()->with('users')->get();
                if ($teams->isEmpty()) {
                    return $teams;
                }
                break;
            default:
                $teams = $user->teams()->with('users')->get();
                break;
        }
        return $teams;
    }
}