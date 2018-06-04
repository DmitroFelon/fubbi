<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\Article\ArticleManager;
use App\Services\Article\ArticleRepository;

/**
 * Class ArticlesController
 * @package App\Http\Controllers\Project
 */
class ArticlesController extends Controller
{
    /**
     * ArticlesController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:articles.index,project')->only(['index']);
        $this->middleware('can:articles.update,project,article')->only(['edit', 'save_social_posts']);
        $this->middleware('can:articles.create,project,App\Models\Article')->only(['create', 'store']);
        $this->middleware('can:articles.delete,project,article')->only(['destroy']);
        $this->middleware('can:articles.accept,project,article')->only(['accept', 'decline']);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Project $project, Request $request, ArticleRepository $articleRepository)
    {
        $articles_query = $articleRepository->searchProject($request->input(), $project);
        $articles = $articles_query->paginate(10);
        $filters['types'] = Article::getTypes($project);
        $filters['statuses'] = [
            ''    => _i('Select status'),
            true  => _i('Accepted'),
            false => _i('Declined')
        ];
        return view('entity.article.index', compact('project', 'articles', 'filters'));
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Project $project)
    {
        $filters['types'] = Article::getTypes($project);
        $filters['ideas'] = array_combine(
            $project->ideas->pluck('id')->toArray(),
            $project->ideas->pluck('theme')->toArray()
        );
        $filters['ideas'][''] = _('Select Idea');
        return view('entity.article.create', compact('project', 'filters'));
    }

    /**
     * @param Project $project
     * @param Article $article
     * @param Request $request
     * @param ArticleManager $articleManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Project $project, Article $article, Request $request, ArticleManager $articleManager)
    {
        $articleManager->create($request, $article, $project);
        return redirect()->action('Project\ArticlesController@index', $project);
    }

    /**
     * @param Project $project
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Project $project, Article $article)
    {
        $articleClient = $project->client_id;
        return view('entity.article.show', compact('project', 'article', 'articleClient'));
    }

    /**
     * @param Project $project
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project, Article $article)
    {
        $article = $project->articles()->findOrFail($article->id);
        return view('entity.article.edit', compact('project', 'article'));
    }

    /**
     * @param Project $project
     * @param Article $article
     * @param ArticleManager $articleManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project, Article $article, ArticleManager $articleManager)
    {
        $articleManager->delete($article);
        return redirect()->action('Project\ArticlesController@index', $project);
    }

    /**
     * @param Project $project
     * @param Article $article
     * @param Request $request
     * @param ArticleManager $articleManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_social_posts(Project $project, Article $article, Request $request, ArticleManager $articleManager)
    {
        $articleManager->saveSocialPosts($project, $article, $request->input());
        return redirect()->back()->with('success', _i('Article updated'));
    }

    /**
     * @param Project $project
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Project $project, Article $article)
    {
        $project->acceptArticle($article->id);
        return redirect()->action('Project\ArticlesController@index', $project);
    }

    /**
     * @param Project $project
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decline(Project $project, Article $article)
    {
        $project->declineArticle($article->id);
        return redirect()->action('Project\ArticlesController@index', $project);
    }

    /**
     * @param Article $article
     * @param Request $request
     * @param ArticleManager $articleManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function rate(Article $article, Request $request, ArticleManager $articleManager)
    {
        $articleManager->rate($request->user(), $article, $request->input('rate'));
        return response()->json(['result' => true]);
    }
}
