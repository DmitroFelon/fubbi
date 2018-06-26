<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\Article\ArticleRepository;
use App\Services\Google\Drive;
use App\Models\Article;
use Illuminate\Http\Request;

/**
 * Class ArticlesController
 * @package App\Http\Controllers\Resources
 */
class ArticlesController extends Controller
{
    /**
     * @var Drive
     */
    protected $drive;

    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * ArticlesController constructor.
     * @param Drive $drive
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        Drive $drive,
        ArticleRepository $articleRepository
    )
    {
        $this->drive = $drive;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $articlesQuery = $this->articleRepository->articlesByRole($request->user());
        $articlesQuery = $this->articleRepository->searchAll($request->input(), $articlesQuery);
        $articles = $articlesQuery->paginate(10);
        $filters['types'] = Article::getAllTypes();
        $filters['statuses'] = [
            ''    => _i('Select status'),
            true  => _i('Accepted'),
            false => _i('Declined')
        ];

        return view('entity.article.index', compact('articles', 'filters'));
    }

    /**
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Article $article)
    {
        return view('entity.article.show', compact('article'));
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function request_access(Request $request, Article $article)
    {
        $this->drive->addPermission($article->google_id, [$request->user()->email => 'commenter']);

        return redirect()->back()->with('success', 'Permissions has been provided');
    }
}
