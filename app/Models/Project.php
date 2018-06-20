<?php

namespace App\Models;


use App;
use App\Facades\ProjectExport;
use App\Models\Interfaces\Invitable;
use App\Models\Traits\hasInvite;
use App\Models\Traits\Project\hasArticles;
use App\Models\Traits\Project\hasConversation;
use App\Models\Traits\Project\hasCycles;
use App\Models\Traits\Project\hasIdeas;
use App\Models\Traits\Project\hasPlan;
use App\Models\Traits\Project\hasServices;
use App\Models\Traits\Project\hasStates;
use App\Models\Traits\Project\hasTeams;
use App\Models\Traits\Project\hasWorkers;
use App\Notifications\Project\Invite;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Kodeine\Metable\Metable;
use Laravel\Cashier\Subscription;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\Media;
use Venturecraft\Revisionable\Revision;

/**
 * Class Project
 *
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \App\User $client
 * @property-read \Illuminate\Database\Eloquent\Collection|Revision[] $revisionHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $workers
 * @method static Builder|\App\Models\Project meta()
 * @mixin \Eloquent
 * @property int $id
 * @property int $client_id
 * @property string $name
 * @property string $description
 * @property string $state
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static Builder|\App\Models\Project whereClientId($value)
 * @method static Builder|\App\Models\Project whereCreatedAt($value)
 * @method static Builder|\App\Models\Project whereId($value)
 * @method static Builder|\App\Models\Project whereName($value)
 * @method static Builder|\App\Models\Project whereState($value)
 * @method static Builder|\App\Models\Project whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property int $subscription_id
 * @property-read \Kalnoy\Nestedset\Collection|\BrianFaust\Commentable\Models\Comment[] $comments
 * @property-read \Laravel\Cashier\Subscription $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereSubscriptionId($value)
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read mixed $plan
 * @property-read mixed $plan_metadata
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Project withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Idea[] $ideas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project\Cycle[] $cycles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection $cycle
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project\Service[] $services
 */
class Project extends Model implements HasMediaConversions, Invitable
{
    use hasCycles;
    use hasServices;
    use hasIdeas;
    use hasStates;
    use hasPlan;
    use hasWorkers;
    use hasTeams;
    use hasArticles;
    use hasInvite;
    use hasConversation;

    use HasMediaTrait;
    use Metable;
    use SoftDeletes;
    use LogsActivity;

    /**
     *
     */
    const TAG_CATEGORY = 'service_type';

    /**
     * @var string
     */
    protected $table = 'projects';

    /**
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * @var array
     */
    protected static $logAttributes = ['name', 'text'];

    /**
     * Collections of project files
     * May contain results of quiz
     * and worker's metarials
     *
     * @var string[]
     */
    public static $media_collections = [
        'article_images',
        'compliance_guideline',
        'logo',
        'ready_content',
    ];

    /**
     * Additional observable events.
     */
    protected $observables = [
        'setState',
        'suspend', // fured when subscription stopped
        'reset', //fired when billing cycle renewed
        'filled',
        'attachWorker',
        'attachWorkers',
        'detachWorker',
        'attachTeam',
        'detachTeam',
        'attachArticle',
        'acceptArticle',
        'declineArticle',
        'lastDeclineArticle', //fires when writer of article has spent 3 attempts for approval
        'acceptReview',
    ];

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string
     */
    protected $metaTable = 'projects_meta';

    /**
     * @var array
     */
    protected $with = ['client', 'metas'];

    /**
     * Project's owner
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Project's Stripe subscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inspirations()
    {
        return $this->hasMany(Inspiration::class);
    }

    /**
     * Upload user's quiz files
     * May contain logo, ready content, images etc
     *
     *
     * @param \Illuminate\Http\Request $request
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function addFiles(Request $request)
    {
        try {
            $files = collect();
            foreach (self::$media_collections as $file_input) {
                $media = $this->addFile($file_input, $request);
                if ($media instanceof Media) {
                    $files->push($media);
                }
            }
            return $files->filter();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return static
     * @throws \Exception
     */
    public function addMetaFiles(Request $request)
    {
        try {
            $files = collect();
            foreach ($request->file() as $file_name => $file) {
                $files->push($this->addFile($file_name, $request));
            }
            return $files->filter();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $file_input
     * @param Request $request
     * @return Media
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    private function addFile($file_input, Request $request)
    {
        try {
            if ($request->hasFile($file_input)) {
                if (is_array($request->file($file_input))) {
                    foreach ($request->file($file_input) as $file) {
                        return $this->addMedia($file)->toMediaCollection($file_input);
                    }
                } else {
                    return $this->addMedia($request->file($file_input))->toMediaCollection($file_input);
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Implementation of App\Models\Interfaces\Invitable interface
     * Used to show project's url in notification
     *
     * @return string
     */
    public function getInvitableUrl()
    {
        return url()->action('Resources\ProjectController@show', $this);
    }

    /**
     * Implementation of App\Models\Interfaces\Invitable interface
     * Used to export project's url in notification
     *
     * @return string
     */
    public function getExportUrl()
    {
        return url()->action('Resources\ProjectController@export', $this);
    }

    /**
     * Implementation of App\Models\Interfaces\Invitable interface
     * Used to invite someone to join this project
     *
     * @return string
     */
    public function getInvitableNotification()
    {
        return Invite::class;
    }

    /**
     * @param $type
     * @return string
     */
    public function getTimelineIcon($type)
    {
        switch ($type) {
            case 'project_worker' :
                return 'fa-users';
                break;
            case 'project_progress' :
                return 'fa fa-line-chart';
                break;
            default:
                return 'fa-file-o';
                break;
        }
    }

    /**
     * @return mixed
     */
    public function metaToView()
    {
        $meta_to_skip = [
            'themes_order',
            'questions',
            'export',
            'conversation_id'
        ];
        $meta         = clone $this->metas;
        $meta->transform(function ($item) use ($meta_to_skip) {
            //skip modified plan attributes
            if (strpos($item->key, 'modificator_') !== false or in_array($item->key, $meta_to_skip)) {
                return null;
            }

            return $item;
        });

        return $meta->filter();
    }

    /**
     * @return static
     */
    public function getAvailableWorkers()
    {
        $users = User::without(['notifications', 'invites'])
                     ->whereNotIn('id', $this->workers->pluck('id')->toArray())
                     ->get(['id', 'first_name', 'last_name', 'email']);

        //prepare and filter users for the view

        $invitable_users = $users->keyBy('id')->transform(function (User $user) {
            if (in_array($user->role, [\App\Models\Role::ADMIN, \App\Models\Role::CLIENT])) {
                return null;
            }

            $role = $user->roles()->first();

            $role_name = ($role) ? $role->display_name : '';

            return $user->name . " - {$role_name}";
        });

        return $invitable_users->filter();
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
     * @param $key
     * @return array
     */
    public function prepareTagsInput($key)
    {
        return (is_array($this->$key)) ? array_combine(array_values($this->$key), array_values($this->$key)) : [];
    }

    /**
     * @return array
     */
    public function prepareTagsInputThemes()
    {
        $themes = $this->ideas()->themes()->get()->pluck('theme')->toArray();
        return array_combine($themes, $themes);
    }


    /**
     * @return string
     */
    public function export()
    {
        try {
            $ready_export = trim($this->getMeta('export'));
            //return path to zip if exist
            if ($ready_export and File::exists(storage_path('app/public/exports/') . $ready_export)) {
                return storage_path('app/public/exports/') . $ready_export;
            }
            return ProjectExport::make($this);
        } catch (\Exception $e) {
            report($e);
            throw new \Exception(_('Somethig wrong happened while project export, please try later: ' . $e->getMessage()));
        }

    }

}
