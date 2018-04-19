<?php

namespace App\Models\Project;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cycle
 *
 * @package App\Models\Project
 * @property int $id
 * @property int $project_id
 * @property string $stripe_plan
 * @property \Carbon\Carbon|null $start_date
 * @property \Carbon\Carbon|null $end_date
 * @property-read \App\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project\Cycle active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project\Cycle whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project\Cycle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project\Cycle whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project\Cycle whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Project\Cycle whereStripePlan($value)
 * @mixin \Eloquent
 */
class Cycle extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'project_id',
        'stripe_plan',
        'start_date',
        'end_date',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param $query
     * @return Cycle
     */
    public function scopeActive($query)
    {
        return $query->latest('id')->first();
    }

}
