<?php

namespace App\Models\Users;

use App\Models\Role;
use App\Models\Traits\HasParentModel;
use App\User;
use Illuminate\Database\Eloquent\Builder;


/**
 * App\Models\Users\Worker
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $avg_rating
 * @property-read mixed $count_negative
 * @property-read mixed $count_positive
 * @property-read \Illuminate\Support\Collection $disabled_notifications
 * @property-read string $name
 * @property-read null $role
 * @property-read mixed $sum_rating
 * @property-read mixed $rating_percent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Ghanem\Rating\Models\Rating[] $ratings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User meta()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Users\Worker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User withRole($role)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Musonza\Chat\Conversations\Conversation[] $conversations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invite[] $invites
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $relatedClientArticles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $relatedWorkerArticles
 */
class Worker extends User
{
    use HasParentModel;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        //get only workers
        static::addGlobalScope('role', function (Builder $builder) {
            $builder->whereHas(Role::TABLE, function ($query) {
                $query->whereIn(Role::COLUMN, Role::WORKERS);
            });
        });
    }
}
