<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Inspiration;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Spatie\MediaLibrary\Media;

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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $inspirations = $request->has('u')
            ? User::findOrFail($request->input('u'))->inspirations()->paginate(10)
            : Auth::user()->inspirations()->paginate(10);

        return view('entity.inspiration.index', compact('inspirations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $inspiration = Auth::user()->inspirations()->create();
        return redirect()->route('inspirations.edit', $inspiration);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $inspiration = $this->inspiration->findOrFail($id);

        return view('entity.inspiration.show', compact('inspiration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $inspiration = $this->inspiration->findOrFail($id);

        return view('entity.inspiration.edit', compact('inspiration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inspiration = $this->inspiration->findOrFail($id);

        $inspiration->update($request->only([
            'questions',
            'trends',
            'stories',
            'transcripts',
            'cta',
        ]));

        return redirect()->route('inspirations.index')->with('success', 'Idea has been saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::user()->inspirations()->findOrFail($id)->delete();

        return redirect()->route('inspirations.index')->with('info', 'Idea has been deleted');
    }

    /**
     * @param $id
     * @return string
     */
    public function storeFile(Request $request, $id, $collection)
    {
        if (!$request->hasFile('files')) {
            return null;
        }

        $media = Auth::user()->inspirations()->findOrFail($id)->addMedia($request->file('files'))
                     ->toMediaCollection($collection);

        $media->url = $this->inspiration->prepareMediaConversion($media);

        return Response::json([$media], 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     */
    public function getFiles(Request $request, $id, $collection)
    {
        $files = $this->inspiration->findOrFail($id)->getMedia($collection);

        $files->transform(function (Media $media) {
            $media->url = $this->inspiration->prepareMediaConversion($media);
            return $media;
        });

        return Response::json($files->filter()->toArray(), 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $file_id
     * @return string
     */
    public function removeFile($id, $file_id)
    {
        Auth::user()->inspirations()->findOrFail($id)->media()->findOrFail($file_id)->delete();
        return Response::json('success', 200);
    }
}
