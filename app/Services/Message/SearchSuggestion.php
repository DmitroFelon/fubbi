<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.06.18
 * Time: 10:07
 */

namespace App\Services\Message;

use Cache;

/**
 * Class SearchSuggestion
 * @package App\Services\Message
 */
class SearchSuggestion
{
    /**
     * @param $conversation
     * @return mixed
     */
    public static function get($conversation)
    {
        Cache::forget($conversation->id . '_user_search_suggestions');
        return Cache::remember(
            $conversation->id . '_user_search_suggestions', 60, function () use ($conversation) {
            $search_suggestions = [];
            $users = $conversation->users;
            foreach($users as $user) {
                array_push($search_suggestions, array('username' => $user->username));
            }
            return $search_suggestions;
        });
    }

    /**
     * @param string $role
     * @return mixed
     */
    public static function toView($conversation)
    {
        return self::get($conversation);
    }
}