<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Invite
 *
 * @property int $id
 * @property int $user_id
 * @property string $invitable_type
 * @property string $invitable_id
 * @property int|null $accepted
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite accepted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite new()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite projects()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite rejected()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite teams()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereInvitableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereInvitableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invite whereUserId($value)
 * @mixin \Eloquent
 */
class Invite extends Model
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'invitable_type',
		'invitable_id',
		'user_id',
	];

	/**
	 * @var array
	 */
	protected $observables = [
		'accepted',
		'rejected'
	];

	/**
	 * @param $query
	 * @return mixed
	 */
	public function scopeTeams($query)
	{
		return $query->where('invitable_type', '=', Team::class);
	}

	/**
	 * @param $query
	 * @return mixed
	 */
	public function scopeProjects($query)
	{
		return $query->where('invitable_type', '=', Project::class);
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
	public function scopeRejected($query)
	{
		return $query->where('accepted', '=', false);
	}

	/**
	 * @return bool
	 */
	public function accept()
	{
		$this->accepted = true;
		$this->save();
		$this->fireModelEvent('accepted', false);
	}

	/**
	 * @return bool
	 */
	public function decline()
	{
		$this->accepted = false;
		$this->fireModelEvent('rejected', false);
		return $this->save();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function invitable()
	{
		return $this->belongsTo(
			$this->invitable_type,
			'invitable_id'
		);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
