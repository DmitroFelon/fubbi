<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;

/**
 * App\Models\Idea
 *
 * @property int $id
 * @property int $project_id
 * @property string $theme
 * @property string|null $points_covered
 * @property string|null $points_avoid
 * @property string|null $references
 * @property int|null $this_month
 * @property int|null $next_month
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereNextMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea wherePointsAvoid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea wherePointsCovered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereReferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereThisMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea questions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea themes()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Idea whereType($value)
 */
class Idea extends Model implements HasMediaConversions
{

    use HasMediaTrait;

    /**
     *
     */
    const TYPE_THEME = 'theme';
    /**
     *
     */
    const TYPE_QUESTION = 'question';

    /**
     * @var array
     */
    protected $fillable = [
        'project_id',
        'type',
        'theme',
        'points_covered',
        'points_avoid',
        'references',
        'this_month',
        'next_month',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @param $query
     * @return Builder
     */
    public function scopeQuestions($query)
    {
        return $query->where('type', self::TYPE_QUESTION);
    }

    /**
     * @param $query
     * @return Builder
     */
    public function scopeThemes($query)
    {
        return $query->where('type', self::TYPE_THEME);
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

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('dropzone')
             ->crop('crop-center', 120, 120)->nonQueued();
    }

}
