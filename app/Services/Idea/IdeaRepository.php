<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.18
 * Time: 11:31
 */

namespace App\Services\Idea;

use App\Models\Idea;
use Spatie\MediaLibrary\Media;

/**
 * Class IdeaRepository
 * @package App\Services\Idea
 */
class IdeaRepository
{
    /**
     * @param Idea $idea
     * @return \Illuminate\Support\Collection
     */
    public function storedFiles(Idea $idea)
    {
        $files = $idea->getMedia();
        $files->transform(function (Media $media) use ($idea) {
            $media->url = $idea->prepareMediaConversion($media);
            return $media;
        });
        return $files;
    }
}