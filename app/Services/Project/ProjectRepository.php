<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.06.18
 * Time: 15:43
 */

namespace App\Services\Project;

use App\User;
use Carbon\Carbon;
use App\Models\Project;
use App\Services\User\SearchSuggestions as UserSearchSuggestions;
use App\Services\Project\SearchSuggestions as ProjectSearchSuggestions;
use App\Models\Role;

/**
 * Class ProjectRepository
 * @package App\Services\Project
 */
class ProjectRepository
{
    /**
     * @var Project
     */
    protected $projects;

    /**
     * ProjectRepository constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->projects = $project;
    }

    /**
     * @param User $user
     * @param array $params
     * @return mixed
     */
    public function projects(User $user, array $params)
    {
        $projects = $this->projects->query();
        switch ($user->role) {
            case \App\Models\Role::ADMIN:
                if (array_key_exists('search', $params) and $params['search'] != '') {
                    $projects = $this->restrictionsByKeyword($projects, $params['search']);
                }
                if (array_key_exists('status', $params) and $params['status'] != '') {
                    $projects = $this->restrictionsByStatus($projects, $params['status']);
                }
                if (array_key_exists('month', $params) and $params['month'] != '') {
                    $projects = $this->restrictionsByMonth($projects, $params['month']);
                }
                $projects = $projects->paginate(10);
                break;
            case \App\Models\Role::CLIENT:
                $projects = $user->projects()->paginate(10);
                if ($projects->isEmpty()) {
                    return redirect()->action('Resources\InspirationController@index');
                }
                break;
            default:
                //if user accepted to the project personally
                $projects = $user->projects()->get();
                $projects = $user->teamProjects()->merge($projects);
                break;
        }
        $filters = $this->filters();
        $projectSuggestions = ProjectSearchSuggestions::toView();
        $userSuggestions = UserSearchSuggestions::toView(Role::CLIENT);
        $searchSuggestions = '["' . implode('", "', $userSuggestions) . '", "' . implode('", "', $projectSuggestions) . '"]';
        $data['projects'] = $projects;
        $data['filters'] = $filters;
        $data['searchSuggestions'] = $searchSuggestions;
        return $data;
    }

    /**
     * @param $projects
     * @param $status
     * @return mixed
     */
    public function restrictionsByStatus($projects, $status)
    {
        if ($status == 'active') {
            $projects = $projects->whereHas('subscription', function ($query) {
                $query->whereNotNull('ends_at');
            });
        } else {
            $projects = $projects->whereHas('subscription', function ($query) {
                $query->whereNull('ends_at');
            });
        }
        return $projects;
    }

    /**
     * @param $projects
     * @param $month
     * @return mixed
     */
    public function restrictionsByMonth($projects, $month)
    {
        $projects = $projects->where('created_at', '>=', Carbon::now()->subMonth($month));
        return $projects;
    }

    /**
     * @param $projects
     * @param $search
     * @return mixed
     */
    public function restrictionsByKeyword($projects, $search)
    {
        $clients = User::search($search)->get();
        if (!$clients->isEmpty()) {
            $in = [];
            foreach ($clients as $client) {
                array_push($in, $client->id);
            }
            $projects = $projects->where(function($query) use($in, $search) {
                $query->whereIn('client_id', $in)->orWhere('name', 'like', '%' . $search . '%');
            });
        }
        else {
            $projects = $projects->where('name', 'like', '%' . $search . '%');
        }
        return $projects;
    }

    /**
     * @return array
     */
    public function filters()
    {
        $filters = [];
        $filters['months'] = [
            ''   => _('All time'),
            '1'  => _('1 month'),
            '3'  => _('3 months'),
            '6'  => _('6 months'),
            '12' => _('12 months'),
        ];
        $filters['status'] = [
            ''         => _i('Any status'),
            'active'   => _i('Active'),
            'deactive' => _i('Inactive'),

        ];
        return $filters;
    }
}