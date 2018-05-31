<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\Article\ArticleRepository;
use App\Services\Article\ArticleExport;
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
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ArticleRepository $articleRepository)
    {
        $articles_query = $articleRepository->articlesByRole($request->user());
        $articles_query = $articleRepository->search($request, $articles_query);
        $articles = $articles_query->paginate(10);
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
     * @param Drive $drive
     * @return \Illuminate\Http\RedirectResponse
     */
    public function request_access(Request $request, Article $article, Drive $drive)
    {
        $google_id = $article->google_id;
        $permissions = [$request->user()->email => 'commenter'];
        $drive->addPermission($google_id, $permissions);
        return redirect()->back()->with('success', 'Permissions has been provided');
    }

    /**
     * @param Article $article
     * @param Request $request
     * @param ArticleExport $articleExport
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function export(Article $article, Request $request, ArticleExport $articleExport)
    {
        return $articleExport->singleExport($article, $request);
    }

    /**
     * @param Request $request
     * @param ArticleExport $articleExport
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function batch_export(Request $request, ArticleExport $articleExport)
    {
        return $articleExport->batchExport($request);
    }
}
