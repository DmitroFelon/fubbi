<?php

namespace App\Services\Idea;

use App\Models\Idea;
use Spatie\MediaLibrary\Media;

/**
 * Class IdeaManager
 * @package App\Services\Idea
 */
class IdeaManager
{
    /**
     * @param array $params
     * @param Idea $idea
     */
    public function update(array $params, Idea $idea)
   {
       $params['completed'] = 1;
       $idea->update($params);
   }

    /**
     * @param Idea $idea
     * @return \Illuminate\Support\Collection
     */
    public function ideaStoredFiles(Idea $idea)
   {
       $files = $idea->getMedia();
       $files->transform(function (Media $media) use ($idea) {
           $media->url = $idea->prepareMediaConversion($media);
           return $media;
       });
       return $files;
   }

    /**
     * @param Idea $idea
     * @param $files
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\Collection
     */
    public function prefillMetaFiles(Idea $idea, $files)
    {
        $result = collect([]);
        foreach ($files as $file) {
            try {
                $media = $idea->addMedia($file)->toMediaCollection();
            } catch (\Exception $e) {
                return response()->json('error', 500);
            }
            $media->url = $idea->prepareMediaConversion($media);
            $result->push($media);
        }
        return $result;
    }
}
