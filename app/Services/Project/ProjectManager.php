<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.06.18
 * Time: 15:42
 */

namespace App\Services\Project;

use App\Models\Project;
use App\Models\Team;
use App\User;
use Spatie\MediaLibrary\Media;
use Illuminate\Http\Request;

/**
 * Class ProjectManager
 * @package App\Services\Project
 */
class ProjectManager
{
    /**
     * @param Project $project
     * @param array $params
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachTeam(Project $project, array $params)
    {
        $team = Team::findOrFail($params['team']);
        $project->attachTeam($params['team']);
        return redirect()->back()->with(
            'info',
            _i('Team: "%s" have been successfully attached to project: "%s"', [$team->name, $project->name])
        );
    }

    /**
     * @param Project $project
     * @param Team $team
     * @return mixed
     */
    public function detachTeam(Project $project, Team $team)
    {
        $data['message_key'] = 'info';
        try {
            $project->detachTeam($team->id);
            $data['message'] = _i("%s has been removed from project", [$team->name]);
        } catch (\Exception $e) {
            report($e);
            $data['message_key'] = 'error';
            $data['message']     = _i("%s is not attached to this project", [$team->name]);
        }
        return $data;
    }

    /**
     * @param Project $project
     * @param array $params
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachUsers(Project $project, array $params)
    {
        $project->attachWorkers(array_keys($params['users']));
        $attached_users = User::whereIn('id', array_keys($params['users']))->get();
        $attached_users_names = implode(', ', $attached_users->pluck('name')->toArray());
        return redirect()->back()->with(
            'info',
            _i('Users: %s have been sucessfully attached to project: "%s"', [$attached_users_names, $project->name])
        );
    }

    /**
     * @param Project $project
     * @param User $user
     * @return mixed
     */
    public function detachUsers(Project $project, User $user)
    {
        $data['message_key'] = 'info';
        try {
            $project->detachWorker($user->id);
            $data['message'] = _i("%s has been removed from project", [$user->name]);
        } catch (\Exception $e) {
            $data['message_key'] = 'error';
            $data['message']     = _i("%s is not attached to this project" . $e->getMessage(), [$user->name]);
        }
        return $data;
    }

    /**
     * @param Project $project
     * @param array $files
     * @param $fileType
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function addFiles(Project $project, array $files, $fileType)
    {
        foreach ($files as $file){
            $project->addMedia($file)->toMediaCollection($fileType);
        }
    }

    /**
     * @param Project $project
     * @param array $params
     * @return \Illuminate\Support\Collection
     */
    public function storedFiles(Project $project, array $params)
    {
        $files = $project->getMedia($params['collection']);
        $files->transform(function (Media $media) use ($project) {
            $media->url = $project->prepareMediaConversion($media);
            return $media;
        });
        return $files;
    }

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