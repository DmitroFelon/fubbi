<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Media;
use App\Services\Idea\IdeaManager;
use App\Http\Requests\IdeaFillRequest;

/**
 * Class IdeaController
 * @package App\Http\Controllers
 */
class IdeaController extends Controller
{
    /**
     * @param Idea $idea
     * @param IdeaManager $ideaManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_stored_idea_files(Idea $idea, IdeaManager $ideaManager)
    {
        $files = $ideaManager->ideaStoredFiles($idea);
        return response()->json($files->filter()->toArray(), 200);
    }

    /**
     * @param Idea $idea
     * @param Request $request
     * @param IdeaManager $ideaManager
     * @return \Illuminate\Http\JsonResponse
     */
    public function prefill_meta_files(Idea $idea, Request $request, IdeaManager $ideaManager)
    {
        $files = collect();
        if($request->has(files)) {
            $files = $ideaManager->prefillMetaFiles($idea, $request->files);
        }
        return response()->json($files, 200);
    }

    /**
     * @param Idea $idea
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove_stored_files(Idea $idea, Media $media)
    {
        $idea->media()->findOrFail($media->id)->delete();
        return response()->json('success', 200);
    }

    /**
     * @param Idea $idea
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Idea $idea)
    {
        return view('entity.idea.theme_fill', compact('idea'));
    }

    /**
     * @param IdeaManager $ideaManager
     * @param IdeaFillRequest $request
     * @param Idea $idea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(IdeaManager $ideaManager, IdeaFillRequest $request, Idea $idea)
    {
        $ideaManager->update($request->input(), $idea);
        return redirect()->route('projects.show', ['project' => $idea->project_id])->with('success', 'Idea has been successfully updated!');
    }
}
