<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

/**
 * Class Inspiration
 * @package App\Models
 */
class Inspiration extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'project_id',
        'questions',
        'trends',
        'stories',
        'transcripts',
        'cta',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('dropzone')
             ->crop('crop-center', 120, 120)->nonQueued();
    }

    /**
     * @param Media $media
     * @return string
     */
    public function prepareMediaConversion(Media $media)
    {
        try {
            return (File::exists($media->getPath('dropzone')))
                ? $media->getFullUrl('dropzone')
                : $media->getFullUrl();
        } catch (\Exception $e) {
            return $media->getFullUrl();
        }
    }
}
