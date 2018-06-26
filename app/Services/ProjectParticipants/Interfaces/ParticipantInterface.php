<?php

namespace App\Services\ProjectParticipants\Interfaces;

use App\Models\Project;

/**
 * Interface ParticipantInterface
 * @package App\Services\ProjectParticipants\Interfaces
 */
interface ParticipantInterface
{
    /**
     * @param Project $project
     * @param array $params
     * @return mixed
     */
    public function attach(Project $project, array $params);

    /**
     * @param Project $project
     * @param $id
     * @return mixed
     */
    public function detach(Project $project, $id);
}