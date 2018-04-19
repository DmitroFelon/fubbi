<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/31/18
 * Time: 12:25 PM
 */

namespace App\Models\Traits\Project;


use App\Models\Idea;

trait hasIdeas
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

}