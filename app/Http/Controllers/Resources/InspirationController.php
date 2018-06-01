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
     * @var Inspiration
     */
    protected $inspiration;

    /**
     * InspirationController constructor.
     * @param Inspiration $inspiration
     */
    public function __construct(Inspiration $inspiration)
    {
        $this->inspiration = $inspiration;
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('inspirations.edit', Auth::user()->inspirations()->create());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('entity.inspiration.show', ['inspiration' => $this->inspiration->findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('entity.inspiration.edit', ['inspiration' => $this->inspiration->findOrFail($id)]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->inspiration->findOrFail($id)->update($request->except(['_method', '_token']));
        return redirect()->route('inspirations.index')->with('success', 'Idea has been saved');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Auth::user()->inspirations()->findOrFail($id)->delete();
        return redirect()->route('inspirations.index')->with('info', 'Idea has been deleted');
    }

    /**
     * @param Request $request
     * @param $id
     * @param $collection
     * @param InspirationManager $inspirationManager
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function storeFile(Request $request, $id, $collection, InspirationManager $inspirationManager)
    {
        if (!$request->hasFile('files')) {
            return null;
        }
        return response()->json([$inspirationManager-$this->storeFile(
                $request->user,
                $request->file('files'),
                $this->inspiration,
                $id,
                $collection)], 200);
    }

    /**
     * @param $id
     * @param $collection
     * @param InspirationRepository $inspirationRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiles($id, $collection, InspirationRepository $inspirationRepository)
    {
        $files = $inspirationRepository->getFiles($this->inspiration, $id, $collection);
        return response()->json($files->filter()->toArray(), 200);
    }

    /**
     * @param $id
     * @param $file_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFile($id, $file_id)
    {
        Auth::user()->inspirations()->findOrFail($id)->media()->findOrFail($file_id)->delete();
        return response()->json('success', 200);
    }
}
