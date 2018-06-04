<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.06.18
 * Time: 16:41
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class GoogleIdScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNotNull('google_id');
    }
}