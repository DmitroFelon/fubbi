<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Helpers\Page;
use App\Models\HelpVideo;
use App\Http\Requests\CreateOrUpdateHelpVideoRequest;
use App\Services\HelpVideo\HelpVideoManager;

/**
 * Class HelpVideosController
 * @package App\Http\Controllers\Resources
 */
class HelpVideosController extends Controller
{
    /**
     * @var HelpVideoManager
     */
    protected $helpVideoManager;

    /**
     * HelpVideosController constructor.
     * @param HelpVideoManager $helpVideoManager
     */
    public function __construct(HelpVideoManager $helpVideoManager)
    {
        $this->helpVideoManager = $helpVideoManager;
    }

    /**
     * @param CreateOrUpdateHelpVideoRequest $request
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateOrUpdateHelpVideoRequest $request, HelpVideo $helpVideo)
    {
        $this->helpVideoManager->create($helpVideo, $request->except(['_token', '_method']));

        return redirect()->action('Resources\HelpVideosController@index')->with('success', _i('Video has been created.'));
    }

    /**
     * @param CreateOrUpdateHelpVideoRequest $request
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateOrUpdateHelpVideoRequest $request, HelpVideo $helpVideo)
    {
        $this->helpVideoManager->update($helpVideo, $request->except(['_token', '_method']));

        return redirect()->route('help_videos.index')->with('success', _i('Video has been updated.'));
    }

    /**
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(HelpVideo $helpVideo)
    {
        $this->helpVideoManager->delete($helpVideo);

        return redirect()->route('help_videos.index')->with('success', _i('Video has been removed.'));
    }

    /**
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(HelpVideo $helpVideo)
    {
        $pages = Page::getAvailablePages()->keyBy('route');

        return view('entity.help_video.edit', compact('helpVideo', 'pages'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('entity.help_video.index', ['videos' => HelpVideo::paginate(20)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('entity.help_video.create', ['pages' => Page::getAvailablePages()->keyBy('route')]);
    }

    /**
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(HelpVideo $helpVideo)
    {
        return view('entity.help_video.show', compact('helpVideo'));
    }
}
