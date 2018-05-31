<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.05.18
 * Time: 14:25
 */

namespace App\Services\Article;

use App\Models\Article;
use App\Models\Project;
use App\Models\Idea;
use Illuminate\Http\Request;
use App\Jobs\GoogleDrive\GoogleDriveCreate;
use App\Jobs\GoogleDrive\GoogleDriveUpload;

/**
 * Class ArticleManager
 * @package App\Services\Article
 */
class ArticleManager
{
    /**
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Article $article)
    {
        try {
            $article->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something wrong happened while deleting the article, please try again later.');
        }
    }

    /**
     * @param Request $request
     * @param Article $article
     */
    public function rate(Request $request, Article $article)
    {
        $request->user()->relatedClientArticles()
            ->findOrFail($article->id)
            ->ratingUnique(
                ['rating' => $request->input('rate')],
                $request->user()
            );
    }

    /**
     * @param Project $project
     * @param Article $article
     * @param Request $request
     */
    public function saveSocialPosts(Project $project, Article $article, Request $request)
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
    }

    /**
     * @param Request $request
     * @param Article $article
     * @param Project $project
     */
    public function create(Request $request, Article $article, Project $project)
    {
        $article->fill($request->except(['_token', '_method']));
        $article->title      = 'title';
        $article->user_id    = $request->user()->id;
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
        $this->uploadFile($request, $article, $project);
        $this->uploadCopyscape($request, $article);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadFile(Request $request, Article $article, Project $project)
    {
        if ($request->hasFile('file')) {
            try {
                $file = $article->addMedia($request->file('file'))->toMediaCollection('file');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            $file_name = $article->generateTitle();
            GoogleDriveUpload::dispatch($project, $article, $file, $file_name);
        } else {
            $file_name = $article->generateTitle();
            GoogleDriveCreate::dispatch($project, $article, $file_name);
        }
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCopyscape(Request $request, Article $article)
    {
        if ($request->hasFile('copyscape')) {
            try {
                $article->addMedia($request->file('copyscape'))->toMediaCollection('copyscape');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }
}