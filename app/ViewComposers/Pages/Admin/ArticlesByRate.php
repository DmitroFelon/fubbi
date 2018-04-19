<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/27/18
 * Time: 9:43 AM
 */

namespace App\ViewComposers\Pages\Admin;


use App\Models\Article;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class ArticlesByRate
 * @package App\ViewComposers\Pages\Admin
 */
class ArticlesByRate
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * ArticlesByRate constructor.
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
        $key = 'article_by_rate'
            . Auth::user()->role
            . $this->request->input('rate')
            . $this->request->input('customer')
            . $this->request->input('date_from')
            . $this->request->input('date_to');

        $articles = Cache::remember(base64_encode($key), Carbon::MINUTES_PER_HOUR * Carbon::HOURS_PER_DAY, function () {
            $rate = $this->request->input('rate');

            if (Auth::user()->role == Role::ADMIN) {
                $query = Article::withRating($rate, ($rate == 3) ? '<=' : '=');
            } else {
                $query = Auth::user()->articles()->withRating($rate, ($rate == 3) ? '<=' : '=');
            }

            if ($this->request->has('customer') and $this->request->input('customer') != '') {
                $user = User::search($this->request->input('customer'))->first();
                if ($user) {
                    $query->whereHas('project', function ($query) use ($user) {
                        $query->where('client_id', $user->id);
                    });
                }
            }

            if ($this->request->has('date_from')) {
                $from = Carbon::createFromFormat('m/d/Y', $this->request->input('date_from'));
                $query->where('created_at', '>', $from);
            }

            if ($this->request->has('date_to')) {
                $to = Carbon::createFromFormat('m/d/Y', $this->request->input('date_to'));
                $query->where('created_at', '<', $to);
            }

            return $query->get();
        });

        return $view->with(compact('articles'));
    }

}