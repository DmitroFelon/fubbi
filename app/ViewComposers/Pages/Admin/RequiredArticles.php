<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/28/18
 * Time: 8:59 AM
 */

namespace App\ViewComposers\Pages\Admin;


use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class RequiredArticles
 * @package App\ViewComposers\Pages\Admin
 */
class RequiredArticles
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * RequiredArticles constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param View $view
     * @return $this
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        if ($user->isWorker()) {
            $user->projects->each(function (Project $project) {
                                
            });
        }

        return $view->with(compact(''));
    }
}