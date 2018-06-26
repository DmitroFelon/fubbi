<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Services\Idea\IdeaManager;
use App\Http\Requests\IdeaFillRequest;

/**
 * Class IdeaController
 * @package App\Http\Controllers
 */
class IdeaController extends Controller
{
    /**
     * @var IdeaManager
     */
    protected $ideaManager;

    /**
     * IdeaController constructor.
     * @param IdeaManager $ideaManager
     */
    public function __construct(IdeaManager $ideaManager)
    {
        $this->ideaManager = $ideaManager;
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
     * @param IdeaFillRequest $request
     * @param Idea $idea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(IdeaFillRequest $request, Idea $idea)
    {
        $this->ideaManager->update($request->input(), $idea);

        return redirect()
            ->route('projects.show', ['project' => $idea->project_id])
            ->with('success', 'Idea has been successfully updated!');
    }
}
