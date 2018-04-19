<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 08/11/17
 * Time: 11:40
 */

namespace App\Models\Traits\Project;

use App\Models\Team;

/**
 * Class hasTeams
 *
 * attach App\Models\Team to App\Models\Project
 *
 * @package App\Models\Traits\Project
 */
trait hasTeams
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }


    /**
     * @param $team_id
     */
    public function attachTeam($team_id)
    {
        $this->teams()->attach($team_id);

        $this->eventData['attachTeam'] = $team_id;

        $this->fireModelEvent('attachTeam', false);
    }


}