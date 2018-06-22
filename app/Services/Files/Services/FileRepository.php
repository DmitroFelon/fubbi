<?php

namespace App\Services\Files\Services;

use Spatie\MediaLibrary\Media;

/**
 * Class FileRepository
 * @package App\Services\Files\Services
 */
class FileRepository
{
    /**
     * @param $filesOwner
     * @param string $collection
     * @return mixed
     */
    public function all($filesOwner, string $collection)
    {
        $files = $filesOwner->getMedia($collection);
        $files->transform(function (Media $media) use ($filesOwner) {
            $media->url = $filesOwner->prepareMediaConversion($media);

            return $media;
        });

        return $files;
    }
}