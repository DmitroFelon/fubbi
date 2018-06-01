<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 11:11
 */

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
     * @param $file
     * @param Inspiration $inspiration
     * @param $id
     * @param $collection
     * @return mixed
     */
    public function storeFile(User $user, $file, Inspiration $inspiration, $id, $collection)
    {
        $media = $user->inspirations()->findOrFail($id)->addMedia($file->toMediaCollection($collection));
        $media->url = $inspiration->prepareMediaConversion($media);
        return $media;
    }
}