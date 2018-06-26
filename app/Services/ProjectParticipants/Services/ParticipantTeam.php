<?php

namespace App\Services\ProjectParticipants\Services;

use App\Models\Project;
use App\Services\ProjectParticipants\Interfaces\ParticipantTeamInterface;

/**
 * Class ParticipantTeam
 * @package App\Services\ProjectParticipants\Services
 */
class ParticipantTeam extends Participant implements ParticipantTeamInterface
{
    /**
     * @param Project $project
     * @param array $id
     */
    protected function attachParticipant(Project $project, array $id)
    {
        $project->attachTeam($id);
    }

    /**
     * @param Project $project
     * @param array $id
     * @return mixed|void
     */
    protected function detachParticipant(Project $project, $id)
    {
        $project->detachTeam($id);
    }
}