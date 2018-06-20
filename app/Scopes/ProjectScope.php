<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProjectScope
 * @package App\Scopes
 */
class ProjectScope implements Scope
{
    /**
     * @param Builder $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->join('users as u', 'u.id', '=', 'projects.client_id')->whereNull('u.deleted_at');
    }
}