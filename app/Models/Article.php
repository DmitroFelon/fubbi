<?php

namespace App\Models;

use App\Models\Project\Cycle;
use App\Models\Project\Service;
use App\Scopes\GoogleIdScope;
use App\Services\Google\Drive;
use App\User;
use BrianFaust\Commentable\Traits\HasComments;
use Carbon\Carbon;
use Ghanem\Rating\Traits\Ratingable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Kodeine\Metable\Metable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;
use Spatie\MediaLibrary\Media;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

/**
 * App\Models\Article
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @mixin \Eloquent
 * @property int $id
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereText($value)
 * @property int $user_id
 * @property int $project_id
 * @property int $accepted
 * @property int $attempts
 * @property string $title
 * @property string $body
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $author
 * @property-read \Kalnoy\Nestedset\Collection|\BrianFaust\Commentable\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereAttempts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereUserId($value)
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article withAnyTags($tags, $type = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article withoutTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article accepted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article declined()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article new()
 * @property string|null $google_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article meta()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereGoogleId($value)
 * @property string $type
 * @property int $active
 * @property-read mixed $avg_rating
 * @property-read mixed $count_negative
 * @property-read mixed $count_positive
 * @property-read mixed $sum_rating
 * @property-read mixed $rating_percent
 * @property-read \App\Models\Project $project
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ghanem\Rating\Models\Rating[] $ratings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article withRating($rating, $compare = '=')
 * @property int|null $idea_id
 * @property-read \App\Models\Idea|null $idea
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereIdeaId($value)
 * @property int $cycle_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Article whereCycleId($value)
 */
class Article extends Model implements HasMedia, HasMediaConversions
{
    use HasMediaTrait;
    use HasComments;
    use SoftDeletes;
    use Metable;
    use HasTags;
    use Ratingable;
    use LogsActivity;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'type',
        'user_id',
        'idea_id'
    ];

    /**
     * @var string
     */
    protected $metaTable = 'article_meta';

    /**
     * @var array
     */
    protected $with = ['project', 'metas'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new GoogleIdScope());
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

    /**
     * @param Media $media
     * @return string
     * @throws \Spatie\MediaLibrary\Exceptions\InvalidConversion
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
     * One article may belong to many projects
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAccepted($query)
    {
        return $query->where('accepted', '=', true);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeDeclined($query)
    {
        return $query->where('accepted', '=', false);
    }

    /**
     * @param $query
     * @param $cycle_id
     * @return mixed
     */
    public function scopeByCycle($query, $cycle_id)
    {
        return $query->where('cycle_id', $cycle_id);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNew($query)
    {
        return $query->where('accepted', '=', null);
    }

    /**
     * @param $query
     * @param $rating
     * @param string $compare
     * @return mixed
     */
    public function scopeWithRating($query, $rating, $compare = '=')
    {
        return $query->whereHas('ratings', function ($query) use ($rating, $compare) {
            $query->where('rating', $compare, $rating);
        });
    }

    /**
     * Attach specific service tag to article
     *
     * @param string $tag
     */
    public function attachTagsHelper($tag)
    {
        $this->attachTag(Tag::findOrCreate($tag, Project::TAG_CATEGORY));
    }

    /**
     * @return mixed
     */
    public function getGooglePath()
    {
        return $this->getMeta('google_path');
    }

    /**
     * @param array $google_path
     */
    public function setGooglePath(array $google_path)
    {
        $this->setMeta('google_path', $google_path);
        $this->save();

    }

    /**
     * @return bool
     */
    public function getIsThisMonthAttribute()
    {
        $project = $this->project;
        if (!$project) {
            return false;
        }

        $current_cycle = $project->cycles()->active()->first();

        if (!$current_cycle) {
            return false;
        }

        if ($current_cycle->id == $this->cycle_id) {
            return true;
        }

        return false;
    }

    /**
     * @param string $as
     * @param null $api
     * @return Media|void
     * @throws \Exception
     */
    public function export($as = Drive::MS_WORD, $api = null)
    {
        try {
            if (!$this->google_id) {
                return;
            }
            $api       = (is_null($api)) ? new Drive() : $api;
            $file      = $api->exportFile($this->google_id, $as);
            $file_name = $this->google_id . '.' . Drive::getExtension($as);
            $path      = storage_path('app/temp/' . $file_name);
            File::put($path, $file);
            $media = $this->addMedia($path)->toMediaCollection('google_export');

            if ($as == Drive::PDF) {
                $file_name = $this->google_id . '.' . Drive::getExtension($as);
                $path      = storage_path('app/temp/pdf/' . $file_name);
                File::put($path, $file);
                $this->addMedia($path)->toMediaCollection('google_export_pdf');
            }

            if (!$media) {
                throw new \Exception(_i('Some error happened while exporting, try later please.'));
            }

            return $media;
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }

    /**
     * @param Project $project
     * @return \Illuminate\Support\Collection
     */
    public static function getTypes(Project $project)
    {
        $types = collect(
            [
                ''                   => _i('Select Type'),
                'Article'            => _i('Article'),
                'Article Outline'    => _i('Article Outline'),
                'Article Topic'      => _i('Article Topic'),
                'Social Posts Texts' => _i('Social Posts Texts'),
            ]
        );
        $project->services()->withType(\App\Models\Project\Service::TYPE_INTEGER)->required()->get()
                ->each(function (Service $service) use ($types, $project) {
                    if (!in_array($service->name, ['articles_count'])) {
                        $types->put($service->name, $service->display_name);
                    }

                });
        return $types;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getAllTypes()
    {
        $project = new Project();
        $types   = collect(
            [
                ''                   => _i('Select Type'),
                'Article Outline'    => _i('Article Outline'),
                'Article Topic'      => _i('Article Topic'),
                'Article'            => _i('Article'),
                'Social Posts Texts' => _i('Social Posts Texts'),
            ]
        );

        $project->services()->get()->each(function ($item) use ($types, $project) {
            $types->put($item, $project->getServiceName($item));
        });

        return $types;
    }

    /**
     * @return string
     */
    public function generateTitle()
    {

        $title       = strval($this->project->name) . ' - ' . strval($this->type) . ' ' . strval($this->id);
        $this->title = $title;
        $this->save();
        return $title;
    }

    /**
     * @param $query
     * @param int $overdue
     * @param string $compare
     */
    public function ScopeOverdue($query, $overdue = 1, $compare = '')
    {
        switch ($overdue) {
            case 1: // not overdue
                $query->whereBetween('articles.updated_at', [Carbon::now()->subDay(1), Carbon::now()]);
                break;
            case 2: // 2 days overdue
                $query->whereBetween('articles.updated_at', [Carbon::now()->subDay(2), Carbon::now()->subDay(1)]);
                break;
            case 3: // 3 days overdue
                $query->whereBetween('articles.updated_at', [0, Carbon::now()->subDay(2)]);
                break;
            default:
                $query->whereBetween('articles.updated_at', [0, Carbon::now()->subDay($overdue - 1)]);
                break;
        }
    }

}
