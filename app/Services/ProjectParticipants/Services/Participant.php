<?php

namespace App\Services\ProjectParticipants\Services;

use App\Models\Project;
use App\Services\ProjectParticipants\Interfaces\ParticipantInterface;

/**
 * Class Participant
 * @package App\Services\ProjectParticipants\Services
 */
abstract class Participant implements ParticipantInterface
{
    /**
     * @param Project $project
     * @param array $id
     * @return mixed
     */
    abstract protected function attachParticipant(Project $project, array $id);

    /**
     * @param Project $project
     * @param array $id
     * @return mixed
     */
    abstract protected function detachParticipant(Project $project, $id);

    /**
     * @param Project $project
     * @param array $id
     * @return mixed|void
     */
    public function attach(Project $project, array $id)
    {
        $this->attachParticipant($project, $id);
    }

    /**
     * @param Project $project
     * @param array $id
     * @return mixed|void
     */
    public function detach(Project $project, $id)
    {
        $this->detachParticipant($project, $id);
    }
}