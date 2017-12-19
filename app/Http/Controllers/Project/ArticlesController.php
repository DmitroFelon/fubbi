<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Jobs\GoogleDriveUpload;
use App\Models\Article;
use App\Models\Project;
use App\Services\Google\Drive;
use Carbon\Carbon;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return view('entity.article.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        return view('entity.article.create', compact('project'));
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

        $article->fill(
            $request->except(['_token', '_method'])
        );

        $article->project_id = $project->id;
        $article->user_id    = Auth::user()->id;

        $article->save();

        if ($request->hasFile('file')) {
            $file      = $article->addMedia($request->file('file'))->toMediaCollection('file');
            $file_name = ($article->title) ? $article->title : $request->file('file')->getClientOriginalName();
            GoogleDriveUpload::dispatch($project, $article, $file, $file_name);
        }

        return redirect()->action('Project\ArticlesController@show', [$project, $article]);

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
        return view('entity.article.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Project $project
     * @param  \App\Models\Article $article
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, Article $article, Request $request)
    {
        //
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
        //
    }
}
