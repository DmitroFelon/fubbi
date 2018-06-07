<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.18
 * Time: 15:53
 */

namespace App\Services\Plan;

use App\Models\Project;
use Stripe\Plan;

/**
 * Class PlanManager
 * @package App\Services\Plan
 */
class PlanManager
{
    /**
     * @param Plan $plan
     * @param $params
     */
    public function updatePlan(Plan $plan, $params)
    {
        $params->transform(
            function ($item) {
                if ($item == 'true' or $item == false) {
                    return ($item == 'true') ? true : false;
                }
                return $item;
            }
        );
        $plan->metadata = $params->toArray();
        $plan->save();
    }

    /**
     * @param array $params
     * @param Project $project
     */
    public function updateProjectPlan(array $params, Project $project)
    {
        collect($params)->each(function ($item, $key) use ($project) {
            $service = $project->services()->whereName($key)->first();
            if ($service) {
                $service->customize(strval($item));
            }
        });
    }
}