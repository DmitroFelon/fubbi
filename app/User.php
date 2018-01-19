<?php

namespace App;

use Activity;
use App\Models\Article;
use App\Models\Interfaces\Invitable;
use App\Models\Invite;
use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use Ghanem\Rating\Traits\Ratingable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Kodeine\Metable\Metable;
use Laravel\Cashier\Billable;
use Laravel\Scout\Searchable;
use Musonza\Chat\Conversations\Conversation;
use Musonza\Chat\Facades\ChatFacade;
use Musonza\Chat\Messages\Message;
use Musonza\Chat\Notifications\MessageSent;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Zizaco\Entrust\Traits\EntrustUserTrait;

/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Annotation[] $annotations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User withRole($role)
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Outline[] $outlines
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @property string|null $deleted_at
 * @property null $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invite[] $invites
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDeletedAt($value)
 */
class User extends Authenticatable implements HasMedia
{
    use Notifiable;
    use Billable;
    use EntrustUserTrait;
    use HasMediaTrait;
    use Searchable;
    use Metable;
    use Ratingable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
    ];

    /**
     * @var string
     */
    protected $metaTable = 'user_meta';

    /**
     * @var array
     */
    protected $searchble = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
    ];

    /**
     * @var array
     */
    protected $with = [
        'roles'
    ];

    /**
     * @var array
     */
    protected $appends = ['role'];

    /**
     * @return array
     */
    public function toSearchableArray()
    {

        return [
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
        ];
    }

    /**
     * @return null
     */
    public function getRoleAttribute()
    {
        return ($this->roles->first())
            ? $this->roles->first()->name
            :
            null;
    }

    /**
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function routeNotificationForPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getDisabledNotificationsAttribute()
    {
        return ($this->getMeta('disabled_notifications'))
            ? collect($this->getMeta('disabled_notifications'))
            : collect();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        if ($this->role == \App\Models\Role::CLIENT) {
            return $this->hasMany(Project::class, 'client_id');
        }

        return $this->belongsToMany(Project::class, 'project_worker', 'user_id', 'project_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user', 'user_id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @param \App\Models\Interfaces\Invitable $whereInvite
     */
    public function inviteTo(Invitable $whereInvite)
    {
        $invite = new Invite(
            [
                'invitable_type' => get_class($whereInvite),
                'invitable_id'   => $whereInvite->getInvitableId(),
                'user_id'        => $this->id,
            ]
        );

        $invite->save();
    }

    /**
     * @param $project_id
     * @return mixed
     */
    public function hasInvitetoProject($project_id)
    {
        return $this->invites()->where('invitable_id', $project_id)->projects()->new()->get()->isNotEmpty();
    }

    /**
     * @param $team_id
     * @return mixed
     */
    public function hasInvitetoTeam($team_id)
    {
        return $this->invites()->where('invitable_id', $team_id)->teams()->new()->get()->isNotEmpty();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invites()
    {
        return $this->hasMany(Invite::class);
    }

    /**
     * @param $project_id
     * @return mixed
     */
    public function getInviteToProject($project_id)
    {
        return $this->invites()->projects()->where('invitable_id', $project_id)->new()->get()->first();
    }

    /**
     * @param $team_id
     * @return mixed
     */
    public function getInviteToTeam($team_id)
    {
        return $this->invites()->teams()->where('invitable_id', $team_id)->new()->get()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations()
    {
        return $this->belongsToMany(
            Conversation::class,
            'mc_conversation_user',
            'user_id',
            'conversation_id'
        )->withTimestamps();
    }

    /**
     * @param int $minutes
     * @return mixed
     */
    public function isActive($minutes = 5)
    {
        return $us = Activity::users($minutes)->where('user_id', $this->id)->first();
    }

    /**
     * @return string
     */
    public function getBadgeColor()
    {
        switch ($this->role) {
            case Role::ADMIN:
                return 'blue-bg';
                break;
            case Role::ACCOUNT_MANAGER:
                return 'red-bg';
                break;
            case Role::CLIENT:
                return 'navy-bg';
                break;
            case Role::WRITER:
            case Role::DESIGNER:
            case Role::RESEARCHER:
                return 'yellow-bg';
                break;
            default:
                return 'lazur-bg';
                break;
        }
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->unreadNotifications()->where('type', '!=', MessageSent::class)->orderBy('created_at', 'asc')
                    ->get();
    }

    /**
     * @return Collection
     */
    public function getMessageNotifications()
    {
        $messages = $this->unreadNotifications()->where('type', '=', MessageSent::class)->get();

        $messages->transform(function ($notification, $key) use ($messages) {
            $message = Message::find($notification->data['message_id']);

            if (!$message) {
                return;
            }

            if ($message->sender->id == Auth::user()->id) {
                $notification->markAsRead();
                return null;
            }

            $notification->message = \Musonza\Chat\Messages\Message::find($message->id);

            $notification->conversation = ChatFacade::conversation($message->conversation_id);

            return $notification;
        });

        $messages = $messages->filter();

        return $messages;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function relatedClientArticles()
    {
        return $this->hasManyThrough(Article::class, Project::class, 'client_id', 'project_id');
    }

    public function relatedWorkerArticles()
    {
        return $this->hasManyThrough(Article::class, Project::class, 'client_id', 'project_id');
    }
}
