<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/5/18
 * Time: 11:57 AM
 */

namespace App\Services\Google;


use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\Log;

trait GoogleDrivePath
{
    /**
     * @return array
     */
    private function composePath()
    {
        $client_name        = $this->project->client->name;
        $project_name       = $this->project->name;
        $subscription_year  = $this->project->subscription->updated_at->format('Y');
        $subscription_month = $this->project->subscription->updated_at->format('F');


        $google_docs_folders = [
            $client_name,
            implode(' - ', [$client_name, $project_name]),
            implode(' - ', [$client_name, $project_name, $subscription_year]),
            implode(' - ', [$client_name, $project_name, $subscription_year, $subscription_month]),
        ];

        return $google_docs_folders;
    }

    /**
     * @param array $path
     * @return array
     * @throws \Exception
     */
    private function createPath(array $path)
    {
        //check is path exist recursively
        $result = $this->drive->isPathExist($path);

        $next_parent = null;

        $result_striped = [];

        //remove "response-" from batch item name
        foreach ($result as $item => $exist) {
            $name                  = strval(str_replace('response-', '', $item));
            $result_striped[$name] = $exist;
        }

        $result = $result_striped;

        //create necessary folders
        foreach ($result as $folder_name => $exist) {

            $folder_id = ($exist)
                ? $exist
                : $this->drive->addFolder($folder_name, $next_parent)->id;

            $result[$folder_name] = $folder_id;

            $next_parent = $folder_id;
        }

        //return folders' ids
        return array_values($result);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function createPermissions()
    {
        $permissions = collect();

        //set article uploader as owner
        $permissions->put($this->article->author->email, 'writer');
        //set project client as commenter
        $permissions->put($this->project->client->email, 'writer');

        //set other workers as writer
        $this->project->workers->each(function (User $worker) use ($permissions) {
            if ($worker->email == $this->article->author->email) {
                return;
            }
            $permissions->put($worker->email, 'writer');
        });

        $admins = User::withRole(Role::ADMIN)->get();

        //set admins as reader
        $admins->each(function (User $admin) use ($permissions) {
            if ($admin->id == $this->article->user_id) {
                return;
            }
            $permissions->put($admin->email, 'writer');
        });


        Log::debug($permissions->toJson());

        return $permissions;
    }
}