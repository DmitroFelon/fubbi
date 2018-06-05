<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/29/18
 * Time: 3:01 PM
 */

namespace App\ViewComposers\Pages\Admin;


use App\Models\Article;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Class OverdueArticles
 * @package App\ViewComposers\Pages\Admin
 */
class OverdueArticles
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * OverdueArticles constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function compose(View $view)
    {
        $key = 'overdue_articles'. $this->request->input('overdue').$this->request->input('customer');
        $articles = Cache::remember(base64_encode($key), Carbon::MINUTES_PER_HOUR * Carbon::HOURS_PER_DAY, function () {
            $overdue = $this->request->input('overdue');
            if (Auth::user()->role == Role::ADMIN) {
                $query = Article::new();
            } else {
                $query = Auth::user()->articles()->new();
            }
            if ($this->request->has('customer') and $this->request->input('customer') != '') {
                $user = User::search($this->request->input('customer'))->first();
                if ($user) {
                    $client_id = $user->id;
                    $query->whereHas('project', function ($query) use ($client_id) {
                        $query->where('client_id', $client_id);
                    });
                }
            }
            $query->overdue($overdue);
            return $query->get();
        });
        return $view->with(compact('articles'));
    }

}