<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/26/18
 * Time: 2:45 PM
 */

namespace App\Services\User;

use App\User;
use Illuminate\Support\Facades\Cache;

/**
 * Class SearchSuggestions
 * @package App\Services\User
 */
class SearchSuggestions
{

    /**
     * @param string $role
     * @return mixed
     */
    public static function get(string $role = '*')
    {
        Cache::forget($role . '_user_search_suggestions');
        return Cache::remember(
            $role . '_user_search_suggestions', 60, function () use ($role) {
            $search_suggestions = collect();
            if (empty($role) or $role == '*') {
                User::all()->map(
                    function (User $user) use ($search_suggestions) {
                        $search_suggestions->push($user->name);
                        $search_suggestions->push($user->email);
                    }
                );
            } else {
                User::withRole($role)->get()->map(
                    function (User $user) use ($search_suggestions) {
                        $search_suggestions->push($user->name);
                        $search_suggestions->push($user->email);
                    }
                );
            }
            return $search_suggestions->toArray();
        });
    }

    /**
     * @param string $role
     * @return mixed
     */
    public static function toView(string $role = '*')
    {
        return self::get($role);
    }
}