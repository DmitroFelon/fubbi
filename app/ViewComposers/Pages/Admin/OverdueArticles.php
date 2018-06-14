<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/29/18
 * Time: 3:01 PM
 */

namespace App\ViewComposers\Pages\Admin;

use App\Models\Role;
use App\Services\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\Article\OverdueArticleRepository;

/**
 * Class OverdueArticles
 * @package App\ViewComposers\Pages\Admin
 */
class OverdueArticles
{

    /**
     * @var array|string
     */
    protected $overdue;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var OverdueArticleRepository
     */
    protected $overdueArticleRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * OverdueArticles constructor.
     * @param Request $request
     * @param OverdueArticleRepository $overdueArticleRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        Request $request,
        OverdueArticleRepository $overdueArticleRepository,
        UserRepository $userRepository
    )
    {
        $this->request = $request;
        $this->overdueArticleRepository = $overdueArticleRepository;
        $this->userRepository = $userRepository;
        $this->overdue = $request->input('overdue');
    }

    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with(['articles' => $this->getArticles()]);
    }

    /**
     * @return \App\Models\Article[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getArticles()
    {
        return $this->checkCustomer() ? $this->getArticlesByCustomer() : $this->getArticlesWithoutCustomer();
    }

    /**
     * @return string
     */
    protected function checkCustomer()
    {
        return trim($this->request->input('customer'));
    }

    /**
     * @return \App\Models\Article[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getArticlesByCustomer()
    {
        $articles = collect([]);
        $user = $this->userRepository->search($this->request->input('customer'));
        if ($user) {
            $articles = $this->overdueArticleRepository->articlesByClient($user->id, $this->overdue);
        }

        return $articles;
    }

    /**
     * @return \App\Models\Article[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getArticlesWithoutCustomer()
    {
        return $this->request->user()->role == Role::ADMIN
            ? $this->overdueArticleRepository->allArticles($this->overdue)
            : $this->overdueArticleRepository->articlesByRelatedProjects($this->request->user()->id, $this->overdue);
    }
}