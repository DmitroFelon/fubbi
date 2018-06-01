<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 11:12
 */

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
     * @param $id
     * @param $collection
     * @return mixed
     */
    public function getFiles(Inspiration $inspiration, $id, $collection)
    {
        $files = $inspiration->findOrFail($id)->getMedia($collection);
        $files->transform(function (Media $media) use ($inspiration) {
            $media->url = $inspiration->prepareMediaConversion($media);
            return $media;
        });
        return $files;
    }
}