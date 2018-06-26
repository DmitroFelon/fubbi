<?php

namespace App\Services\Project;

/**
 * Class ProjectManager
 * @package App\Services\Project
 */
class ProjectManager
{
    /**
     * @param $project
     * @param $state
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setState($project, $state)
    {
        try {
            $project->setState($state);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}