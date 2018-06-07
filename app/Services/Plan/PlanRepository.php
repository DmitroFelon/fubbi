<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.06.18
 * Time: 15:19
 */

namespace App\Services\Plan;

use Illuminate\Support\Facades\Cache;
use Stripe\Plan;
use Laravel\Cashier\Subscription;
use App\Models\Project;

/**
 * Class PlanRepository
 * @package App\Services\Plan
 */
class PlanRepository
{
    /**
     * @return mixed
     */
    public function plans()
    {
        $plans = Cache::remember('public_plans', 1440, function () {
            $filtered_plans = collect();
            foreach (Plan::all()->data as $plan) {
                $filtered_plans->push($plan);
            }
            return $filtered_plans;
        }
        );
        $plans->each(
            function (Plan $plan, $i) {
                $plan->meta     = $plan->metadata->jsonSerialize();
                $plan->projects = Subscription::where('stripe_plan', $plan->id)->count();
            }
        );
        return $plans;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function plan($id)
    {
        $plan = Plan::retrieve($id);
        $data['nickname'] = $plan->nickname;
        $data['id'] = $plan->id;
        $data['meta'] = collect($plan->metadata->jsonSerialize());
        $data['projects'] = Project::whereIn(
            'subscription_id',
            Subscription::select('id')->where('stripe_plan', $plan->id)->get()
        )->get();
        return $data;
    }
}