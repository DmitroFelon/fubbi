<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Helpers\Page;
use App\Models\HelpVideo;
use App\Http\Requests\CreateOrUpdateHelpVideoRequest;

/**
 * Class HelpVideosController
 * @package App\Http\Controllers\Resources
 */
class HelpVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('entity.help_video.index', ['videos' => HelpVideo::paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pages = Page::getAvailablePages()->keyBy('route');
        return view('entity.help_video.create', compact('pages'));
    }

    /**
     * @param CreateOrUpdateHelpVideoRequest $request
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateOrUpdateHelpVideoRequest $request, HelpVideo $helpVideo)
    {
        $helpVideo->fill($request->except(['_token', '_method']));
        $helpVideo->save();
        return redirect()->action('Resources\HelpVideosController@index')->with('success', _i('Video has been created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\Response
     */
    public function show(HelpVideo $helpVideo)
    {
        return view('entity.help_video.show', compact('helpVideo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\Response
     */
    public function edit(HelpVideo $helpVideo)
    {
        $pages = Page::getAvailablePages()->keyBy('route');
        return view('entity.help_video.edit', compact('helpVideo', 'pages'));
    }

    /**
     * @param CreateOrUpdateHelpVideoRequest $request
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateOrUpdateHelpVideoRequest $request, HelpVideo $helpVideo)
    {
        $helpVideo->fill($request->except(['_token', '_method']));
        $helpVideo->save();
        return redirect()->action('Resources\HelpVideosController@index')->with('success', _i('Video has been updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param HelpVideo $helpVideo
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(HelpVideo $helpVideo)
    {
        $helpVideo->delete();
        return redirect()->action('Resources\HelpVideosController@index')->with('success', _i('Video has been removed.'));
    }
}
