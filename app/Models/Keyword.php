<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Keyword
 *
 * @package App\Models
 * @property int $id
 * @property int $idea_id
 * @property string $type
 * @property string $text
 * @property int $accepted
 * @property-read \App\Models\Idea $idea
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword accepted()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword questions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword suggestions()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereIdeaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Keyword whereType($value)
 * @mixin \Eloquent
 */
class Keyword extends Model
{

    const TYPE_SUGGESTION = 'suggestions';
    const TYPE_QUESTION   = 'questions';

    /**
     * @var array
     */
    protected $fillable = [
        'idea_id',
        'text',
        'type',
        'accepted'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * @param $query
     * @return Builder
     */
    public function scopeAccepted($query)
    {
        return $query->where('accepted', true);
    }

    public function scopeSuggestions($query)
    {
        return $query->where('type', self::TYPE_SUGGESTION);
    }

    public function scopeQuestions($query)
    {
        return $query->where('type', self::TYPE_QUESTION);
    }

}
