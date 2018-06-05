<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/13/18
 * Time: 4:16 PM
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

class DeclinedArticlesComposer
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $key = 'declined_articles'
            . Auth::user()->role
            . $this->request->input('customer')
            . $this->request->input('date_from')
            . $this->request->input('date_to');
        $declined_articles = Cache::remember(base64_encode($key), Carbon::MINUTES_PER_HOUR * Carbon::HOURS_PER_DAY, function () {
            if (Auth::user()->role == Role::ADMIN) {
                $query = Article::declined();
            } else {
                $query = Auth::user()->articles()->declined();
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
            if ($this->request->has('date_from')) {
                $from = Carbon::createFromFormat('m/d/Y', $this->request->input('date_from'));
                $query->where('updated_at', '>', $from);
            }
            if ($this->request->has('date_to')) {
                $to = Carbon::createFromFormat('m/d/Y', $this->request->input('date_to'));
                $query->where('updated_at', '<', $to);
            }
            return $query->get();
        });
        return $view->with(compact('declined_articles', 'declined_articles_count'));
    }
}