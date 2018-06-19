<?php

namespace App\Services\Plan;

use App\Models\Project;
use Stripe\Plan;
use App\Models\Traits\ResponseMessage;

/**
 * Class PlanManager
 * @package App\Services\Plan
 */
class PlanManager
{
    use ResponseMessage;

    /**
     * @param Plan $plan
     * @param $params
     * @return \App\Services\Response\ResponseDTO
     */
    public function update(Plan $plan, $params)
    {
        if ($params->isEmpty()) {

            return $this->make('You have to add metadata to plan!', 'error');
        }
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

        return $this->make('Plan has been modified successfully!', 'success');

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