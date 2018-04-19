<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Jobs\GoogleDrive\GoogleDriveCreate;
use App\Jobs\GoogleDrive\GoogleDriveUpload;
use App\Models\Article;
use App\Models\Idea;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
     * Display a listing of the resource.
     *
     * @param \App\Models\Project $project
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project, Request $request)
    {
        $articles_query = $project->articles();

        if ($request->has('type') and $request->input('type') != '') {
            $articles_query->where('type', $request->input('type'));
        }
        if ($request->has('active') and $request->input('active') != '') {
            $current_cycle = $project->cycles()->latest('id')->first();
            if ($current_cycle) {
                $articles_query->where('cycle_id', $current_cycle->id);
            }
        }
        if ($request->has('status') and $request->input('status') != '') {
            if ($request->input('status') == 1) {
                $articles_query->accepted();
            } else {
                $articles_query->declined();
            }
        }

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
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
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
     * Store a newly created resource in storage.
     *
     * @param \App\Models\Project $project
     * @param \App\Models\Article $article
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     * @internal param Drive $google
     */
    public function store(Project $project, Article $article, Request $request)
    {

        //fill article
        $article->fill($request->except(['_token', '_method']));

        $article->title      = 'title';
        $article->user_id    = Auth::user()->id;
        $article->project_id = $project->id;

        $idea = Idea::find($request->input('idea_id'));

        if ($idea) {
            //if article should be published this month
            if ($idea->this_month) {
                $current_cycle = $project->cycles()->latest('id')->first();
                if ($current_cycle) {
                    $article->cycle_id = $current_cycle->id;
                }
            }
        }

        //if article should be published next month
        $article->cycle_id = 0;
        $article->save();

        //upload file to google docs
        if ($request->hasFile('file')) {
            $file      = $article->addMedia($request->file('file'))->toMediaCollection('file');
            $file_name = $article->generateTitle();
            GoogleDriveUpload::dispatch($project, $article, $file, $file_name);
        } else {
            $file_name = $article->generateTitle();
            GoogleDriveCreate::dispatch($project, $article, $file_name);
        }

        //upload copyscape screenshot
        if ($request->hasFile('copyscape')) {
            $article->addMedia($request->file('copyscape'))->toMediaCollection('copyscape');
        }

        return redirect()->action('Project\ArticlesController@index', $project);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Article $article)
    {
        $article = $project->articles()->findOrFail($article->id);

        return view('entity.article.show', compact('project', 'article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project $project
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Article $article)
    {
        $article = $project->articles()->findOrFail($article->id);

        return view('entity.article.edit', compact('project', 'article'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Project $project
     * @param  \App\Models\Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Article $article)
    {
        $article->delete();
        return redirect()->action('Project\ArticlesController@index', $project);
    }

    /**
     * @param Project $project
     * @param Article $article
     * @param Request $request
     * @return mixed
     */
    public function save_social_posts(Project $project, Article $article, Request $request)
    {

        $article = $project->articles()->findOrFail($article->id);

        $article->setMeta('socialposts', $request->input('socialposts'));

        $article->type = $request->input('type');


        $tags = collect(explode(',', $request->input('tags')));

        $article->syncTags([]);

        $tags->each(function ($tag) use ($article) {
            $article->attachTagsHelper($tag);
        });

        $article->save();

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
     * Set unique rating
     *
     * @param Project $project
     * @param Article $article
     * @param Request $request
     * @return bool
     */
    public function rate(Project $project, Article $article, Request $request)
    {
        Auth::user()->relatedClientArticles()
            ->findOrFail($article->id)
            ->ratingUnique(
                ['rating' => $request->input('rate')],
                Auth::user()
            );

        return Response::json(['result' => true]);
    }

}
