<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.06.18
 * Time: 16:26
 */

namespace App\Services\Subscription;

use Laravel\Cashier\Subscription;
use Stripe\Plan;

/**
 * Class SubscriptionRepository
 * @package App\Services\Subscription
 */
class SubscriptionRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function countById($id)
    {
        return Subscription::where('stripe_plan', $id)->count();
    }

    /**
     * @return mixed
     */
    public function allPlans()
    {
        return Plan::all()->data;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function idByStripePlan($id)
    {
        return Subscription::select('id')->where('stripe_plan', $id)->get();
    }
}