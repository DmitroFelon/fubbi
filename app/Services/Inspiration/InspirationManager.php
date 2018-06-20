<?php

namespace App\Services\Inspiration;

use App\Models\Inspiration;
use App\User;

/**
 * Class InspirationManager
 * @package App\Services\Inspiration
 */
class InspirationManager
{
    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(User $user)
    {
        return $user->inspirations()->create();
    }

    /**
     * @param Inspiration $inspiration
     * @param array $params
     */
    public function update(Inspiration $inspiration, array $params)
    {
        $inspiration->update($params);
    }

    /**
     * @param Inspiration $inspiration
     * @throws \Exception
     */
    public function delete(Inspiration $inspiration)
    {
        $inspiration->delete();
    }

    /**
     * @param $files
     * @param Inspiration $inspiration
     * @param $collection
     * @return \Spatie\MediaLibrary\Media
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
     */
    public function storeFile($files, Inspiration $inspiration, $collection)
    {
        foreach ($files as $file) {
            $media = $inspiration->addMedia($file)->toMediaCollection($collection);
        }
        $media->url = $inspiration->prepareMediaConversion($media);

        return $media;
    }
}