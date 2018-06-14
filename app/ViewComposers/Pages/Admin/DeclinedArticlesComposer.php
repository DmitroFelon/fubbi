<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/13/18
 * Time: 4:16 PM
 */

namespace App\ViewComposers\Pages\Admin;

use App\Models\Role;
use App\Services\Article\DeclinedArticleRepository;
use App\Services\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class DeclinedArticlesComposer
 * @package App\ViewComposers\Pages\Admin
 */
class DeclinedArticlesComposer
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var DeclinedArticleRepository
     */
    protected $declinedArticleRepository;

    /**
     * @var array
     */
    protected $timeConstrains;

    /**
     * DeclinedArticlesComposer constructor.
     * @param Request $request
     * @param UserRepository $userRepository
     * @param DeclinedArticleRepository $declinedArticleRepository
     */
    public function __construct(
        Request $request,
        UserRepository $userRepository,
        DeclinedArticleRepository $declinedArticleRepository
    )
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->declinedArticleRepository = $declinedArticleRepository;
        $this->timeConstrains = $this->request->only(['date_from', 'date_to']);
    }

    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with(['declined_articles' => $this->getDeclinedArticles()]);
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     */
    protected function getDeclinedArticles()
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
     * @return \Illuminate\Support\Collection|mixed
     */
    protected function getArticlesByCustomer()
    {
        $declinedArticles = collect([]);
        $user = $this->userRepository->search($this->request->input('customer'));
        if ($user) {
            $declinedArticles = $this->declinedArticleRepository->articlesByClient($user->id, $this->timeConstrains);
        }

        return $declinedArticles;
    }

    /**
     * @return mixed
     */
    protected function getArticlesWithoutCustomer()
    {
        return $this->request->user()->role == Role::ADMIN
            ? $this->declinedArticleRepository->allArticles($this->timeConstrains)
            : $this->declinedArticleRepository->articlesByRelatedProjects($this->request->user()->id, $this->timeConstrains);
    }
}