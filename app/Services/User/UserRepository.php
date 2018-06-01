<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 16:10
 */

namespace App\Services\User;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function findByResetToken($token)
    {
        return DB::table('reset_email')->select('email')->where('token', $token)->first();
    }
}