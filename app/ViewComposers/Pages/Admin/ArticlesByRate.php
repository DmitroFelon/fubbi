<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/27/18
 * Time: 9:43 AM
 */

namespace App\ViewComposers\Pages\Admin;


use App\Models\Role;
use App\Services\Article\ArticlesByRateRepository;
use App\Services\User\UserRepository;
use Illuminate\Http\Request;
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
     * @var ArticlesByRateRepository
     */
    protected $articlesByRateRepository;

    /**
     * @var array
     */
    protected $timeConstrains;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var array|string
     */
    protected $rate;

    /**
     * ArticlesByRate constructor.
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ArticlesByRateRepository $articlesByRateRepository
     */
    public function __construct(
        Request $request,
        UserRepository $userRepository,
        ArticlesByRateRepository $articlesByRateRepository
    )
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->articlesByRateRepository = $articlesByRateRepository;
        $this->timeConstrains = $request->only(['date_from', 'date_to']);
        $this->rate = $request->input('rate');
    }

    /**
     * @param View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with(['articles' => $this->getArticlesByRate()]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getArticlesByRate()
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
     * @return \Illuminate\Support\Collection
     */
    protected function getArticlesByCustomer()
    {
        $articlesByRate = collect([]);
        $user = $this->userRepository->search($this->request->input('customer'));
        if ($user) {
            $articlesByRate = $this->articlesByRateRepository->articlesByClient($user->id, $this->timeConstrains, $this->rate);
        }

        return $articlesByRate;
    }

    /**
     * @return mixed
     */
    protected function getArticlesWithoutCustomer()
    {
        return $this->request->user()->role == Role::ADMIN
            ? $this->articlesByRateRepository->allArticles($this->timeConstrains, $this->rate)
            : $this->articlesByRateRepository->articlesByRelatedProjects($this->request->user()->id, $this->timeConstrains, $this->rate);
    }
}