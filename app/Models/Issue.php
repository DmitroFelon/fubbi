<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

/**
 * Class Issue
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $state
 * @property string $title
 * @property string $body
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\Spatie\Tags\Tag[] $tags
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Issue withAnyTags($tags, $type = null)
 * @mixin \Eloquent
 */
class Issue extends Model
{
    use HasTags;

    const STATE_CREATED = 0;
    const STATE_FIXED   = 1;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'state'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function printState()
    {
        return ($this->state == self::STATE_FIXED)
            ? '<span class="label label-primary">Fixed</span>'
            : '<span class="label label-danger">New</span>';
    }
}
