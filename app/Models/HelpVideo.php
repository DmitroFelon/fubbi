<?php

namespace App\Models;

use App\Models\Helpers\Page;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HelpVideo
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $url
 * @property \Page $page
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed|string $player
 * @property-read string $thumbnail
 * @property-read mixed|string $youtube_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpVideo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpVideo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpVideo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpVideo wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpVideo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HelpVideo whereUrl($value)
 * @mixin \Eloquent
 */
class HelpVideo extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'page'
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param $value
     * @return mixed|string
     */
    public function getYoutubeIdAttribute($value)
    {
        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->url, $match)) {
                return $match[1];
            }
        }

        return $this->url;
    }

    /**
     * @param $value
     * @return mixed|string
     */
    public function getPlayerAttribute($value)
    {
        return 'https://www.youtube.com/embed/' . $this->youtube_id;
    }

    /**
     * @param $value
     * @return string
     */
    public function getThumbnailAttribute($value)
    {
        return 'https://img.youtube.com/vi/' . $this->youtube_id . '/default.jpg';
    }

    /**
     * @param $value
     */
    public function setPageAttribute($value)
    {
        $this->attributes['page'] = is_array($value)
            ? json_encode($value)
            : json_encode([$value]);
    }

    /**
     * @param $value
     * @return Page
     */
    public function getPageAttribute($value)
    {
        return Page::getByRoute(json_decode($value));
    }

}
