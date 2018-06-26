<?php

namespace App\Services\Files\Services;

use App\Models\Project;
use App\Models\Traits\ResponseMessage;
use App\Services\Files\Interfaces\ProjectFilesInterface;

/**
 * Class ProjectFiles
 * @package App\Services\Files\Services
 */
class ProjectFiles extends Files implements ProjectFilesInterface
{
    use ResponseMessage;

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Project $project)
    {
        $data['success'] = 1;
        try {
            $data['readyProject'] = $project->export();

            return $data;
        } catch (\Exception $e) {
            report($e);
            $data['success'] = 0;
            $data['response'] = $this->make('Something wrong happened while requirements export. Please, try later.', 'error');

            return $data;
        }
    }
}