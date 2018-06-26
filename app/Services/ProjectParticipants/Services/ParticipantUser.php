<?php

namespace App\Services\ProjectParticipants\Services;

use App\Models\Project;
use App\Services\ProjectParticipants\Interfaces\ParticipantUserInterface;

/**
 * Class ParticipantUser
 * @package App\Services\ProjectParticipants\Services
 */
class ParticipantUser extends Participant implements ParticipantUserInterface
{
    /**
     * @param Project $project
     * @param array $id
     */
    protected function attachParticipant(Project $project, array $id)
    {
        $project->attachWorkers($id);
    }

    /**
     * @param Project $project
     * @param array $id
     * @return mixed|void
     */
    protected function detachParticipant(Project $project, $id)
    {
        $project->detachWorker($id);
    }
}