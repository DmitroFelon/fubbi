<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\MediaLibrary\Media;

/**
 * Class IdeaController
 * @package App\Http\Controllers
 */
class IdeaController extends Controller
{
    /**
     * @param Idea $idea
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_stored_idea_files(Idea $idea)
    {
        $files = $idea->getMedia();

        $files->transform(function (Media $media) use ($idea) {
            $media->url = $idea->prepareMediaConversion($media);
            return $media;
        });

        return Response::json($files->filter()->toArray(), 200);
    }

    /**
     * @param Idea $idea
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function prefill_meta_files(Idea $idea, Request $request)
    {
        $files = collect();

        if ($request->hasFile('files')) {
            foreach ($request->files as $file) {
                $media      = $idea->addMedia($file)->toMediaCollection();
                $media->url = $idea->prepareMediaConversion($media);
                $files->push($media);
            }
        }

        return Response::json($files, 200);
    }

    /**
     * @param Idea $idea
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove_stored_files(Idea $idea, Media $media)
    {
        $idea->media()->findOrFail($media->id)->delete();
        return Response::json('success', 200);
    }

    public function show(Idea $idea)
    {
        return view('entity.idea.show', compact('idea'));
    }
}
