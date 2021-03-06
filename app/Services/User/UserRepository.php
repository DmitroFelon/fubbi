<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 16:10
 */

namespace App\Services\User;

use Illuminate\Support\Facades\DB;
use App\User;

/**
 * Class UserRepository
 * @package App\Services\User
 */
class UserRepository
{
    /**
     * @param $token
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
     */
    public function findByResetToken($token)
    {
        return DB::table('reset_email')->select('email')->where('token', $token)->first();
    }

    /**
     * @param $username
     * @return User|\Illuminate\Database\Eloquent\Model|null
     */
    public function findByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function search($params)
    {
        return User::search($params)->first();
    }

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return User::withTrashed()->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return User::withTrashed()->findOrFail($id);
    }

    /**
     * @param array $ids
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findByIds(array $ids)
    {
        return User::whereIn('id', $ids)->get();
    }
}