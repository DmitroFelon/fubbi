<?php


namespace App\Models;

use App\Models\Interfaces\Invitable;
use App\Models\Traits\hasInvite;
use App\Notifications\Team\Invite;
use App\User;
use Ghanem\Rating\Traits\Ratingable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * App\Models\Team
 *
 * @property-read mixed $avg_rating
 * @property-read mixed $count_negative
 * @property-read mixed $count_positive
 * @property-read mixed $sum_rating
 * @property-read mixed $rating_percent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ghanem\Rating\Models\Rating[] $ratings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereUpdatedAt($value)
 * @property int|null $owner_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereOwnerId($value)
 * @property string|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Team whereDeletedAt($value)
 */
class Team extends Model implements Invitable
{
    use Ratingable;
    use hasInvite;
    use Notifiable;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'owner_id'
    ];

    /**
     * @var array
     */
    protected $with = [
        'users',
        'owner',
        'projects'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user', 'team_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * @return string
     */
    public function getInvitableUrl():string
    {
        return url()->action('Resources\TeamController@show', $this);
    }

    /**
     * @return mixed
     */
    public function getInvitableNotification()
    {
        return Invite::class;
    }

    /**
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->owner->email;
    }

    /**
     * @return mixed
     */
    public function routeNotificationForPhone()
    {
        return $this->owner->phone;
    }

    /**
     * @param Team|null $team
     * @return Collection
     */
    public function getInvitableUsers(Team $team = null)
    {
        $users = User::without(['notifications', 'invites'])->get(['id', 'first_name', 'last_name', 'email']);
        return $users->keyBy('id')->transform(function (User $user) use ($team) {
            if ($user->role == Role::CLIENT) {
                return null;
            }

            if ($team) {
                if ($user->role == Role::CLIENT) {
                    return null;
                }

                if ($user->hasInvitetoTeam($team->id)) {
                    return null;
                }

                if ($user->teams()->find($team->id)) {
                    return null;
                }

                if ($user->id == $team->owner_id) {
                    return null;
                }
            }
            return $user->name;
        })->filter();


    }

    /**
     * @return Collection
     */
    public function getPossibleOwners()
    {
        $users = User::without(['notifications', 'invites'])->get(['id', 'first_name', 'last_name', 'email']);
        return $users->keyBy('id')->transform(function (User $user) {
            if ($user->role == Role::CLIENT) {
                return null;
            }
            return $user->name;
        })->filter();
    }


}
