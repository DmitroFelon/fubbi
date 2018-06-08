<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.06.18
 * Time: 8:35
 */

namespace App\Services\Subscription;

use App\Models\Project;
use App\User;
use Illuminate\Support\Facades\Session;

/**
 * Class SubscriptionManager
 * @package App\Services\Subscription
 */
class SubscriptionManager
{
    /**
     * @param User $user
     * @param Project $project
     * @param array $params
     * @param $state
     * @return Project
     */
    public function subscriptionCreate(User $user, Project $project, array  $params, $state)
    {
        $data['error'] = '';
        $plan_id = $params['plan_id'];
        $subscription = $user->newSubscription($params['project_name'], $plan_id)->create($params['stripeToken']);
        $project->client_id = $user->id;
        $project->name = $params['project_name'];
        $project->subscription_id = $subscription->id;
        try {
            $project->setState($state);
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
            return $data;
        }
        $project->save();
        try {
            $project->setServices($plan_id);
        } catch (\Exception $e) {
            $data['error'] = $e->getMessage();
            return $data;
        }
        $project->setCycle($plan_id);
        //set quiz flag in case user will click "back" button
        Session::put('quiz', $project->id);
        $data['project'] = $project;
        return $project;
    }
}