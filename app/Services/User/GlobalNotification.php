<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 2/8/18
 * Time: 2:04 PM
 */

namespace App\Services\User;


use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class GlobalNotification
 * @package App\Services\User
 */
class GlobalNotification
{
    /**
     * @var
     */
    protected $user;

    /**
     *
     */
    public function make()
    {
        if (!Auth::check()) {
            return;
        }
        $role       = Auth::user()->role;
        $this->user = Auth::user();
        if (method_exists($this, $role)) {
            call_user_func([$this, $role]);
        }

    }

    /**
     *
     */
    private function client()
    {
        $filling_states = [
            ProjectStates::QUIZ_FILLING,
        ];
        
        $projects = $this->user->projects()->whereIn('state', $filling_states)->get();

        if ($projects->isNotEmpty()) {
            $message = 'Please, continue filling project: <br>';

            $projects->each(function (Project $project) use (&$message) {
                $message .= '<a href="' . action('Resources\ProjectController@edit', [
                        $project,
                        's' => $project->state
                    ]) . '">' . $project->name . '</a><br>';
            });

            $url = Request::url();

            if (strpos($url, 'settings') or strpos($url, 'projects') or strpos($url, 'research')) {
                return;
            }

            $this->push('info', $message);
        }

    }

    /**
     *
     */
    public function admin()
    {

    }

    /**
     *
     */
    private function account_manager()
    {

    }

    /**
     *
     */
    private function writer()
    {

    }

    /**
     *
     */
    private function designer()
    {

    }

    /**
     *
     */
    private function editor()
    {

    }

    /**
     *
     */
    private function researcher()
    {

    }

    /**
     * @param string $type
     * @param string $value
     */
    private function push(string $type, string $value)
    {
        Session::push($type, $value);
    }

}
