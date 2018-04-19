<?php

namespace App\Models\Project;

use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 *
 * @package App\Models\Project
 * @property int $id
 * @property int $project_id
 * @property string $name
 * @property string $display_name
 * @property int|mixed $default
 * @property int|mixed $custom
 * @property string $type
 * @property-read mixed $value
 * @property-read \App\Models\Project $project
 * @method static Builder|\App\Models\Project\Service customized()
 * @method static Builder|\App\Models\Project\Service whereCustom($value)
 * @method static Builder|\App\Models\Project\Service whereDefault($value)
 * @method static Builder|\App\Models\Project\Service whereDisplayName($value)
 * @method static Builder|\App\Models\Project\Service whereId($value)
 * @method static Builder|\App\Models\Project\Service whereName($value)
 * @method static Builder|\App\Models\Project\Service whereProjectId($value)
 * @method static Builder|\App\Models\Project\Service whereType($value)
 * @method static Builder|\App\Models\Project\Service withType($type)
 * @mixin \Eloquent
 */
class Service extends Model
{
    /**
     * @const string
     */
    const TYPE_INTEGER = 'integer';
    /**
     * @const string
     */
    const TYPE_BOOLEAN = 'boolean';
    /**
     * @const string
     */
    const TYPE_RANGE = 'range';
    /**
     * @const string
     */
    const TYPE_STRING = 'string';

    /**
     * @var array
     */
    protected $fillable = [
        'project_id',
        'name',
        'display_name',
        'default',
        'custom',
        'type'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param Builder $query
     */
    public function scopeRequired(Builder $query)
    {
        $empty = ['', 0, false, 'false', 'False', 'No', 'no'];
        $query->whereNotIn('default', $empty)->orWhereNotIn('custom', $empty);
    }

    /**
     * @param Builder $query
     * @param $type
     * @return mixed
     */
    public function scopeWithType(Builder $query, $type)
    {
        return $query->where('type', '=', $type);
    }

    /**
     * @param  Builder $query
     */
    public function scopeCustomized(Builder $query)
    {
        return $query->where('custom', '!=', null);
    }

    /**
     * Get casted value
     *
     * @param $value
     * @return int|mixed
     */
    public function getDefaultAttribute($value)
    {
        $value = $this->castFromType($value);

        return $value;
    }

    /**
     * Get casted value
     *
     * @param $value
     * @return int|mixed
     */
    public function getCustomAttribute($value)
    {
        return $this->castFromType($value);
    }

    /**
     * Get current value
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        $custom = $this->getOriginal('custom');

        $value = (!is_null($custom))
            ? $custom
            : $this->default;

        return $value;
    }

    /**
     * @return mixed
     */
    public function getPrintValueAttribute()
    {

        $value = $this->value;

        $value = ($this->type === self::TYPE_BOOLEAN)
            ? ($value) ? 'Yes' : 'No'
            : $value;

        $value = ($value) ? $value : 'No';

        return (is_array($value))
            ? implode('-', $value)
            : $value;
    }

    /**
     * Cast value depends on type
     *
     *
     * @param $value
     * @return int|mixed
     */
    private function castFromType($value)
    {

        switch ($this->type) {
            case self::TYPE_INTEGER :
                $value = intval($value);
                break;
            case self::TYPE_BOOLEAN :
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            case self::TYPE_RANGE :
                $value = explode('-', $value);
                break;
        }

        return $value;
    }

    /**
     * @param $value
     */
    public function customize($value)
    {

        $this->custom = strval($value);

        if ($this->custom == $this->default) {
            $this->custom = null;
        }

        $this->save();
    }

    /**
     * Restore default value
     */
    public function restore()
    {
        $this->custom = null;
        $this->save();
    }


}
