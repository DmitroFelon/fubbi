<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 03/11/17
 * Time: 12:00
 */

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\FlashMessage;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    
    protected $request;
    
    /**
     * DashboardController constructor.
     *
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
        $user = Auth::user();

        $role = $user->role;
        
        $action = (isset($action)) ? '.'.$action : '';
        
        if (View::exists("pages.{$role}.{$page}{$action}")) {
            return view("pages.{$role}.{$page}{$action}");
        }

        return abort(404);
    }
}