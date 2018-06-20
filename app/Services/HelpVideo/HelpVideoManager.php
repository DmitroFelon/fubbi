<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.06.18
 * Time: 17:21
 */

namespace App\Services\HelpVideo;


use App\Models\HelpVideo;

/**
 * Class HelpVideoManager
 * @package App\Services\HelpVideo
 */
class HelpVideoManager
{
    /**
     * @param HelpVideo $helpVideo
     * @param array $params
     */
    public function create(HelpVideo $helpVideo, array $params)
    {
        $helpVideo->fill($params);
        $helpVideo->save();
    }

    /**
     * @param HelpVideo $helpVideo
     * @throws \Exception
     */
    public function delete(HelpVideo $helpVideo)
    {
        $helpVideo->delete();
    }

    /**
     * @param HelpVideo $helpVideo
     * @param array $params
     */
    public function update(HelpVideo $helpVideo, array $params)
    {
        $helpVideo->update($params);
    }
}