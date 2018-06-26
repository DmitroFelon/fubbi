<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Inspiration;
use App\Models\Project;
use App\Services\Files\Interfaces\ArticleFilesInterface;
use App\Services\Files\Interfaces\InspirationFilesInterface;
use App\Services\Files\Interfaces\ProjectFilesInterface;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * @var ArticleFilesInterface
     */
    protected $articleFiles;

    /**
     * @var ProjectFilesInterface
     */
    protected $projectFiles;

    /**
     * @var InspirationFilesInterface
     */
    protected $inspirationFiles;

    /**
     * FileController constructor.
     * @param ArticleFilesInterface $articleFiles
     * @param ProjectFilesInterface $projectFiles
     * @param InspirationFilesInterface $inspirationFiles
     */
    public function __construct(
        ArticleFilesInterface $articleFiles,
        ProjectFilesInterface $projectFiles,
        InspirationFilesInterface $inspirationFiles
    )
    {
        $this->articleFiles     = $articleFiles;
        $this->projectFiles     = $projectFiles;
        $this->inspirationFiles = $inspirationFiles;
    }

    /**
     * @param Project $project
     * @param Article $article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeArticleFilesForm(Project $project, Article $article)
    {
        return view('entity.article.tabs.add-files', ['article' => $article, 'project' => $project]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeArticleFiles(Request $request, Article $article, string $collection)
    {
        try {
            $files = $this->articleFiles->store($request->file('files'), $article, $collection);

            return response()->json($files, 200);
        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param Article $article
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticleFiles(Article $article, string $collection)
    {
        $files = $this->articleFiles->get($article, $collection);

        return response()->json($files, 200);
    }

    /**
     * @param Article $article
     * @param string $fileId
     */
    public function deleteArticleFile(Article $article, string $fileId)
    {
        $this->articleFiles->delete($article, $fileId);
    }

    /**
     * @param Article $article
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportArticle(Article $article, Request $request)
    {
        $data = $this->articleFiles->prepareExport($article, $request->input());
        if ($data['success'] == 1) {

            return response()->download($data['fullPath'], $data['name']);
        }

        return redirect()->back()->with($data['response']->status, $data['response']->message);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportArticles(Request $request)
    {
        $data = $this->articleFiles->prepareBatchExport($request->input());
        if ($data['success'] == 1) {

            return response()->download($data['fullPath']);
        }

        return redirect()->back()->with($data['response']->status, $data['response']->message);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeProjectFilesForm(Project $project)
    {
        return view('entity.project.tabs.add-files', ['project' => $project]);
    }

    /**
     * @param Request $request
     * @param Project $project
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeProjectFiles(Request $request, Project $project, string $collection)
    {
        try {
            $files = $this->projectFiles->store($request->file('files'), $project, $collection);

            return response()->json($files, 200);
        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);
        }

    }

    /**
     * @param Project $project
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProjectFiles(Project $project, string $collection)
    {
        $files = $this->projectFiles->get($project, $collection);

        return response()->json($files, 200);
    }

    /**
     * @param Project $project
     * @param string $fileId
     */
    public function deleteProjectFile(Project $project, string $fileId)
    {
        $this->projectFiles->delete($project, $fileId);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportProject(Project $project)
    {
        $data = $this->projectFiles->export($project);
        if ($data['success'] == 1) {

            return response()->download($data['readyProject']);
        }

        return redirect()->back()->with($data['response']->status, $data['response']->message);
    }

    /**
     * @param Request $request
     * @param Inspiration $inspiration
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeInspirationFiles(Request $request, Inspiration $inspiration, string $collection)
    {
        try {
            $files = $this->inspirationFiles->store($request->file('files'), $inspiration, $collection);

            return response()->json($files, 200);
        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * @param Inspiration $inspiration
     * @param string $collection
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInspirationFiles(Inspiration $inspiration, string $collection)
    {
        $files = $this->inspirationFiles->get($inspiration, $collection);

        return response()->json($files, 200);
    }

    /**
     * @param Inspiration $inspiration
     * @param string $fileId
     */
    public function deleteInspirationFile(Inspiration $inspiration, string $fileId)
    {
        $this->inspirationFiles->delete($inspiration, $fileId);
    }
}
