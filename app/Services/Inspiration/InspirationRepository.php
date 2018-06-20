<?php

namespace App\Services\Inspiration;

use App\Models\Inspiration;
use App\User;
use Spatie\MediaLibrary\Media;

/**
 * Class InspirationRepository
 * @package App\Services\Inspiration
 */
class InspirationRepository
{
    /**
     * @param User $user
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchAll(User $user, array $params)
    {
        return array_key_exists('u', $params)
            ? User::findOrFail($params['u'])->inspirations()->paginate(10)
            : $user->inspirations()->paginate(10);
    }

    /**
     * @param Inspiration $inspiration
     * @param $collection
     * @return \Illuminate\Support\Collection
     */
    public function getFiles(Inspiration $inspiration, $collection)
    {
        $files = $inspiration->getMedia($collection);
        $files->transform(function (Media $media) use ($inspiration) {
            $media->url = $inspiration->prepareMediaConversion($media);

            return $media;
        });

        return $files;
    }
}