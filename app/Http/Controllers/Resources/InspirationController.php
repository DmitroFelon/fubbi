<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Inspiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Inspiration\InspirationRepository;
use App\Services\Inspiration\InspirationManager;

/**
 * Class InspirationController
 * @package App\Http\Controllers\Resources
 */
class InspirationController extends Controller
{
    /**
     * @var InspirationManager
     */
    protected $inspirationManager;

    /**
     * @var InspirationRepository
     */
    protected $inspirationRepository;

    /**
     * InspirationController constructor.
     * @param InspirationManager $inspirationManager
     * @param InspirationRepository $inspirationRepository
     */
    public function __construct(
        InspirationManager $inspirationManager,
        InspirationRepository $inspirationRepository
    )
    {
        $this->inspirationManager = $inspirationManager;
        $this->inspirationRepository = $inspirationRepository;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('inspirations.edit', $this->inspirationManager->create(Auth::user()));
    }

    /**
     * @param Request $request
     * @param Inspiration $inspiration
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Inspiration $inspiration)
    {
        $this->inspirationManager->update($inspiration, $request->except(['_method', '_token']));

        return
            redirect()
            ->route('inspirations.index')
            ->with('success', 'Idea has been saved');
    }

    /**
     * @param Inspiration $inspiration
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Inspiration $inspiration)
    {
        $this->inspirationManager->delete($inspiration);

        return
            redirect()
            ->route('inspirations.index')
            ->with('info', 'Idea has been deleted');
    }

    /**
     * @param Request $request
     * @param InspirationRepository $inspirationRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, InspirationRepository $inspirationRepository)
    {
        return view('entity.inspiration.index', ['inspirations' => $inspirationRepository->searchAll($request->user(), $request->input())]);
    }

    /**
     * @param Inspiration $inspiration
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Inspiration $inspiration)
    {
        return view('entity.inspiration.show', ['inspiration' => $inspiration]);
    }

    /**
     * @param Inspiration $inspiration
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Inspiration $inspiration)
    {
        return view('entity.inspiration.edit', ['inspiration' => $inspiration]);
    }
}
