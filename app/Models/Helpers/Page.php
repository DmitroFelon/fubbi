<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/10/18
 * Time: 9:58 PM
 */

namespace App\Models\Helpers;


/**
 * Class Page
 * @package App\Models\Helpers
 */
use App\Models\HelpVideo;
use App\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request as RequestFacade;

/**
 * Class Page
 * @package App\Models\Helpers
 */
class Page
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $route;
    /**
     * @var array
     */
    public $params;

    /**
     * @var array
     */
    public $roles;

    /**
     * Page constructor.
     * @param string $route
     * @param array|null $params
     * @param string|null $name
     * @param array $roles
     */
    public function __construct(string $route, array $params, string $name, array $roles = [])
    {
        $this->name   = $name;
        $this->route  = $route;
        $this->params = $params;
    }

    /**
     * @return Collection
     */
    public static function getAvailablePages()
    {
        return collect([
            new self('*', [], 'All Pages', [Role::CLIENT]),
            new self('projects.index', [], _('All Projects'), [Role::CLIENT]),
            new self('projects.show', [], _('Specific Project'), [Role::CLIENT]),
            new self('projects.create', [], _('Create Project'), [Role::CLIENT]),
            new self('projects.edit', [], _i('Edit Project'), [Role::CLIENT]),
            new self('projects.edit?' . ProjectStates::PLAN_SELECTION, ['s' => ProjectStates::PLAN_SELECTION], _i('Project on %s', [ProjectStates::PLAN_SELECTION]), [Role::CLIENT]),
            new self('projects.edit?' . ProjectStates::QUIZ_FILLING, ['s' => ProjectStates::QUIZ_FILLING], _i('Project on %s', [ProjectStates::QUIZ_FILLING]), [Role::CLIENT]),
            new self('projects.edit?' . ProjectStates::MANAGER_REVIEW, ['s' => ProjectStates::MANAGER_REVIEW], _i('Project on %s', [ProjectStates::MANAGER_REVIEW]), [Role::CLIENT]),
            new self('projects.edit?' . ProjectStates::CLIENT_REVIEW, ['s' => ProjectStates::CLIENT_REVIEW], _i('Project on %s', [ProjectStates::CLIENT_REVIEW]), [Role::CLIENT]),
            new self('project.articles.index', [], _('All Articles'), [Role::CLIENT]),
            new self('project.articles.show', [], _('Specific Article'), [Role::CLIENT]),
            new self('project.articles.create', [], _('Create Article'), [Role::CLIENT]),
        ]);
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Prepare for select input
     *
     * @return $this
     */
    public static function toView()
    {
        return self::getAvailablePages()->keyBy('route');
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param array $route
     * @return Collection
     */
    public static function getByRoute(array $route)
    {
        return self::getAvailablePages()->whereIn('route', $route);
    }

    /**
     * @return Collection
     */
    public static function getRelatedVideos()
    {
        $videos = HelpVideo::all();
        $appropriate = collect();
        $videos->each(function (HelpVideo $video) use ($appropriate) {
            if (!isset(RequestFacade::route()->action['as'])) {
                return;
            }
            $current_route = RequestFacade::route()->action['as'];
            foreach ($video->page as $page) {

                //show on all pages
                if ($page->route == '*') {
                    $appropriate->push($video);
                    return;
                }

                //get base route withou params
                $route = explode('?', $page->route);
                if (!isset($route[0])) {
                    return;
                }

                //compare routes
                if ($current_route != $route[0]) {
                    return;
                }

                //compare params if exist
                if (!empty($page->params)) {
                    foreach ($page->params as $key => $value) {
                        if (!RequestFacade::has($key)) {
                            return;
                        }

                        if (RequestFacade::input($key) != $value) {
                            return;
                        }
                    }
                }

                $appropriate->push($video);

                return;
            }
        });
        return $appropriate;
    }
}
