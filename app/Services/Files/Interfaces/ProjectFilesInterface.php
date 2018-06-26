<?php

namespace App\Services\Files\Interfaces;

use App\Models\Project;

/**
 * Interface ProjectFilesInterface
 * @package App\Services\Files\Interfaces
 */
interface ProjectFilesInterface extends FileInterface
{
    /**
     * @param Project $project
     * @return mixed
     */
    public function export(Project $project);
}