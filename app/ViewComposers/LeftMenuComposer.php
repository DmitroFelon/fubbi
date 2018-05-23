<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 02/11/17
 * Time: 17:52
 */

namespace App\ViewComposers;

use App\Models\Helpers\ProjectStates;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class LeftMenuComposer
 *
 * @package App\ViewComposers
 */
class LeftMenuComposer
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $page;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * LeftMenuComposer constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->user    = Auth::user();
        $this->request = $request;
        $this->page    = $request->path();
    }

    /**
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view)
    {

        if (Auth::check()) {
            $role = $this->user->role;
            if (!$role) {
                $role = Role::CLIENT;
            }
            $links = $this->{$role}();

            $links = collect($links);

            if ($this->user->isWorker() or $this->user->role == Role::ADMIN) {
                $links->push(
                    [
                        'name'  => 'Dashboard',
                        'url'   => action('DashboardController@dashboard'),
                        'icon'  => 'fa fa-dashboard',
                        'order' => 150,
                    ]
                );
            }
            $links->push(
                [
                    'name'  => 'Messages',
                    'url'   => action('Resources\MessageController@index'),
                    'icon'  => 'fa fa-envelope',
                    'order' => 100,
                ]
            );
            $links->push(
                [
                    'name'  => 'Settings',
                    'url'   => action('SettingsController@index'),
                    'icon'  => 'fa fa-gear',
                    'order' => 100,
                ]
            );
            $links->push(
                [
                    'name'  => 'Issues',
                    'url'   => action('Resources\IssueController@index'),
                    'icon'  => 'fa fa-bug',
                    'order' => 100,
                ]
            );
            $links = $links->sortBy('order', SORT_NUMERIC, true);

        } else {
            $links = $this->guest();
        }

        $view->with('items', $links);
    }

    /**
     * @return array
     */
    public function guest()
    {
        return [
            'Login'    => action('Auth\LoginController@login'),
            'Register' => action('Auth\RegisterController@register'),
        ];
    }

    /**
     * @return array
     */
    public function admin()
    {
        return [
            [
                'name'  => 'Users',
                'url'   => action('Resources\UserController@index'),
                'icon'  => 'fa fa-user',
                'order' => 100,
            ],
            [
                'name'  => 'Teams',
                'url'   => action('Resources\TeamController@index'),
                'icon'  => 'fa fa-users',
                'order' => 100,
            ],
            [
                'name'  => 'Projects',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],
            [
                'name'  => 'Plans',
                'url'   => action('Resources\PlanController@index'),
                'icon'  => 'fa fa-gear',
                'order' => 100,
            ],
            [
                'name'  => 'Articles',
                'url'   => action('Resources\ArticlesController@index'),
                'icon'  => 'fa fa-file-word-o',
                'order' => 100,
            ],
            [
                'name'  => 'Charges',
                'url'   => action('ChargesController@index'),
                'icon'  => 'fa fa-dollar',
                'order' => 100,
            ],
            [
                'name'  => 'Help Videos',
                'url'   => action('Resources\HelpVideosController@index'),
                'icon'  => 'fa fa-youtube',
                'order' => 100,
            ],
        ];
    }

    /**
     * @return array
     */
    public function client()
    {
        $links = [
            [
                'name'  => 'Dashboard',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],
            [
                'name'  => 'Content',
                'url'   => action('Resources\ArticlesController@index'),
                'icon'  => 'fa fa-files-o',
                'order' => 100,
            ],
            [
                'name'  => 'Research',
                'url'   => action('ResearchController@index'),
                'icon'  => 'fa fa-search',
                'order' => 100,
            ],
            [
                'name'  => 'Ideas',
                'url'   => action('Resources\InspirationController@index'),
                'icon'  => 'fa fa-lightbulb-o',
                'order' => 100,
            ],
        ];

        $filling_states = [
            ProjectStates::QUIZ_FILLING,
        ];

        $projects = $this->user->projects()->whereIn('state', $filling_states)->get();

        if ($projects->isNotEmpty()) {
            $project = $projects->first();

            $links[] = [
                'name'  => 'Quiz',
                'url'   => action('Resources\ProjectController@edit', [$project, 's' => $project->state]),
                'icon'  => 'fa fa-check',
                'order' => 110,
            ];
        }

        return $links;

    }

    /**
     * @return array
     */
    public function account_manager()
    {
        return [
            [
                'name'  => 'Projects',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],
            [
                'name'  => 'Teams',
                'url'   => action('Resources\TeamController@index'),
                'icon'  => 'fa fa-users',
                'order' => 100,
            ]
        ];
    }

    /**
     * @return array
     */
    public function writer()
    {
        return [
            [
                'name'  => 'Projects',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],
        ];
    }

    /**
     * @return array
     */
    public function editor()
    {
        return [
            [
                'name'  => 'Projects',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],
        ];
    }

    /**
     * @return array
     */
    public function designer()
    {
        return [
            [
                'name'  => 'Projects',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],

        ];
    }

    /**
     * @return array
     */
    public function researcher()
    {
        return [
            [
                'name'  => 'Projects',
                'url'   => action('Resources\ProjectController@index'),
                'icon'  => 'fa fa-file-o',
                'order' => 100,
            ],

        ];
    }
}
