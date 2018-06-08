<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.06.18
 * Time: 16:50
 */

namespace App\Services\Thrivecart;

use App\Models\Project;
use App\Services\Subscription\SubscriptionManager;
use App\Services\User\UserManager;
use Stripe\Customer;
use Stripe\Subscription;
use App\User;
use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Helpers\ProjectStates;

/**
 * Class ThrivacartManager
 * @package App\Services\Thrivecart
 */
class ThrivacartManager
{
    /**
     * @param UserManager $userManager
     * @param SubscriptionManager $subscriptionManager
     * @param $requestParams
     * @param Project $project
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function create(UserManager $userManager, SubscriptionManager $subscriptionManager, $requestParams, Project $project)
    {
        $product_id = $requestParams['base_product'];
        //stripe customer id
        $customer_identifier = $requestParams['customer_identifier'];
        //form data
        $customer            = $requestParams['customer'];
        //subscription ids, get first
        $subscription_id     = collect($requestParams['subscriptions'])->first();
        $plan_id             = config('fubbi.thrive_cart_plans')[$product_id];
        $stripe_customer     = Customer::retrieve($customer_identifier);
        $stripe_subscription = Subscription::retrieve($subscription_id);
        $customer_card       = collect($stripe_customer->sources->data)->first();
        $user                = User::whereEmail($customer['email'] ?? '')->first();
        if (!$user) {
            $tmp_password = str_random(8);
            $customer['password'] = Hash::make($tmp_password);
            $customer['phone'] = $customer['contactno'] ?? '';
            $customer['stripe_id'] = $stripe_subscription->customer;
            $customer['trial_ends_at'] = Carbon::createFromTimestamp($stripe_subscription->trial_end);
            $customer['card_brand'] = $customer_card->brand;
            $customer['card_last_four'] = $customer_card->last4;
            $customer['address_line_1'] = $customer_card->address_line1;
            $customer['zip'] = $customer_card->address_zip;
            $customer['city'] = $customer_card->address_city;
            $customer['country'] = $customer_card->address_country;
            $customer['state'] = $customer['address']['state'] ?? '';
            $customer['role'] = Role::CLIENT;
            try {
                $userManager->userCreate($user, $customer);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
        $params['plan_id'] = $plan_id;
        $params['project_name'] = $customer['business_name'] ?? 'Project #' . strval($requestParams['order_id']);
        $params['stripeToken'] = $customer_identifier;
        $subscriptionManager->subscriptionCreate($user, $project, $params, ProjectStates::QUIZ_FILLING);

    }
}