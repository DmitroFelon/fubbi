<?php

namespace App\Services\Inspiration;

use App\User;

/**
 * Class InspirationRepository
 * @package App\Services\Inspiration
 */
class InspirationRepository
{
    /**
     * @param User $user
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchAll(User $user, array $params)
    {
        return array_key_exists('u', $params)
            ? User::findOrFail($params['u'])->inspirations()->paginate(10)
            : $user->inspirations()->paginate(10);
    }
}