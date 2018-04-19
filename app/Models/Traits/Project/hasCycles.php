<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/31/18
 * Time: 12:23 PM
 */

namespace App\Models\Traits\Project;

use App\Models\Project\Cycle;

/**
 * Class hasCycles
 * @package App\Models\Traits\Project
 */
trait hasCycles
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getCycleAttribute($query)
    {
        return $this->cycles()->latest('id')->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cycles()
    {
        return $this->hasMany(Cycle::class);
    }

    /**
     * @param string $stripe_plan
     */
    public function setCycle(string $stripe_plan = null)
    {

        if (is_null($stripe_plan)) {
            $stripe_plan = $this->subscription->stripe_plan;
        }

        $this->cycles()->create([
            'stripe_plan' => $stripe_plan,
            'start_date'  => now(),
            'end_date'    => now()->addMonth(),
        ]);
    }

}