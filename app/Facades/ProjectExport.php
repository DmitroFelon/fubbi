<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/29/18
 * Time: 7:32 PM
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class ProjectExport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'export';
    }
}