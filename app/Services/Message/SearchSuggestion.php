<?php

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
    protected static function get($conversation)
    {
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
     * @param $conversation
     * @return mixed
     */
    public function toView($conversation)
    {
        return self::get($conversation);
    }
}