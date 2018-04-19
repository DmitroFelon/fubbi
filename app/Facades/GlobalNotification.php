<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 2/8/18
 * Time: 2:02 PM
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class GlobalNotification extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'global_notification';
    }
}