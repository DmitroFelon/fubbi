<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 08/11/17
 * Time: 11:30
 */

namespace App\Models\Traits\Project;

use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class hasWorkers
 *
 * attach App\User to App\Models\Project
 *
 * @package App\Models\Traits\Project
 */
trait hasWorkers
{

    /**
     * @var array
     */
    public $eventData = [];

    /**
     * @param $worker_id
     */
    public function attachWorker($worker_id)
    {
        if ($this->workers()->find($worker_id)) {
            return;
        }

        $this->workers()->attach($worker_id);

        $this->eventData['attachWorker'] = $worker_id;
        $this->fireModelEvent('attachWorker', false);
    }


    /**
     * @param $worker_ids
     */
    public function attachWorkers($worker_ids)
    {
        $this->workers()->attach($worker_ids);

        $this->eventData['attachWorkers'] = $worker_ids;
        $this->fireModelEvent('attachWorkers', false);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workers()
    {
        return $this->belongsToMany(User::class, 'project_worker', 'project_id', 'user_id');
    }

    /**
     * @param $worker_id
     */
    public function detachWorker($worker_id)
    {
        $this->workers()->detach($worker_id);

        $this->eventData['detachWorker'] = $worker_id;
        $this->fireModelEvent('detachWorker', false);
    }

    /**
     * @param $args
     */
    public function syncWorkers($args)
    {
        $this->workers()->sync($args);

        $this->fireModelEvent('syncWorkers', false);
    }

    /**
     * @param null $role
     * @return bool
     */
    public function hasWorker($role = null)
    {
        return !array_key_exists(
            (is_null($role)) ? Auth::user()->role : $role,
            $this->requireWorkers()
        );
    }

    /**
     * @return array
     */
    public function requireWorkers()
    {
        if ($this->teams->isNotEmpty()) {
            return [];
        }

        $required_workers = [
            \App\Models\Role::WRITER          => _i('Writer'),
            \App\Models\Role::DESIGNER        => _i('Designer'),
            \App\Models\Role::ACCOUNT_MANAGER => _i('Account manager'),
            \App\Models\Role::RESEARCHER      => _i('Researcher'),
        ];

        /*
         * \App\Models\Role::EDITOR => _i('Editor')-  removed for now
         * */

        $has_worker = [];

        $this->workers()->each(
            function (User $user) use (&$has_worker, $required_workers) {
                if (isset($required_workers[$user->role])) {
                    $has_worker[$user->role] = $required_workers[$user->role];
                }
            }
        );

        return array_diff($required_workers, $has_worker);
    }


    /**
     * @return mixed
     */
    public function getManager()
    {
        return $this->workers()->withRole(Role::ACCOUNT_MANAGER)->first();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isManager(User $user)
    {
        $manager = $this->getManager();

        if ($manager and $manager->id == $user->id) {
            return true;
        }

        return false;
    }

}