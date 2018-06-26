<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 03/11/17
 * Time: 12:00
 */

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\User\SearchSuggestions;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * DashboardController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $page
     * @param string $action
     * @return \Illuminate\Support\Facades\View|null
     */
    public function index($page = 'home', $action = 'main', $id = null)
    {

        if (!Auth::check()) {
            //return view('landing');
        }

        $user = Auth::user();

        $role = $user->role;

        if ($page == 'home') {
            //set dynamic home page by role

            $redirect = $this->composeRedirect();
            if ($redirect) {
                return $redirect;
            }
        }

        $action = (isset($action)) ? '.' . $action : '';

        if (View::exists("pages.{$role}.{$page}{$action}")) {
            return view("pages.{$role}.{$page}{$action}");
        }

        return abort(404);
    }

    /**
     * Return home page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function composeRedirect()
    {
        $user = Auth::user();
        $role = $user->role;

        switch ($role) {
            case \App\Models\Role::CLIENT:
                return $this->client();
                break;
            case \App\Models\Role::ACCOUNT_MANAGER:
            case \App\Models\Role::WRITER:
            case \App\Models\Role::DESIGNER:
            case \App\Models\Role::RESEARCHER:
            case \App\Models\Role::EDITOR:
                return $this->workers();
                break;
            case \App\Models\Role::ADMIN:
                return $this->admin();
                break;
            default:
                return null;
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function client()
    {
        $user = Auth::user();
        if ($user->projects()->count() == 1) {

            return redirect()->route('projects.show', $user->projects()->first());
        }
        elseif ($user->projects()->count() > 1) {

            return redirect()->route('projects.index');
        }
        else {

            return redirect()->route('inspirations.index');
        }
    }

    /**
     * @return mixed
     */
    private function workers()
    {
        return redirect()->action('Resources\ProjectController@index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function admin()
    {
        return redirect()->action("DashboardController@dashboard");
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function dashboard(Request $request)
    {
        $clients = Cache::remember('clients', 60, function () {
            return User::withRole(Role::CLIENT)->get();
        });

        $search_suggestions = SearchSuggestions::toView(Role::CLIENT);
        $search_suggestions = '["' . implode('", "', $search_suggestions) . '"]';

        $date_from = ($request->has('date_from'))
            ? $request->input('date_from')
            : now()->subYear(1)->format('m/d/Y');

        $date_to = ($request->has('date_to'))
            ? $request->input('date_to')
            : now()->format('m/d/Y');

        return view('pages.admin.dashboard.index', compact('clients', 'date_from', 'date_to', 'search_suggestions'));
    }

    /**
     * @param string $view
     * @return View
     */
    public function preloadView(string $view)
    {
        if (!View::exists($view)) {
            abort(404);
        }

        return view($view);
    }
}
