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
        if (Auth::user()->role == Role::ADMIN) {
            $query = Article::declined();
        } else {
            $query = Auth::user()->articles()->declined();
        }

        if ($this->request->has('customer') and $this->request->input('customer') > 0) {
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
            $query->where('created_at', '>', $from);
        }

        if ($this->request->has('date_to')) {
            $to = Carbon::createFromFormat('m/d/Y', $this->request->input('date_to'));
            $query->where('created_at', '<', $to);
        }

        $declined_articles       = $query->take(10)->get();
        $declined_articles_count = $query->count();

        return $view->with(compact('declined_articles', 'declined_articles_count'));
    }
}