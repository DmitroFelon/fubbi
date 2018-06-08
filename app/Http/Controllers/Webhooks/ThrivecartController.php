<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use App\Models\Role;
use App\Services\User\UserManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Stripe\Customer;
use Stripe\Subscription;

/**
 * Class TrivecartController
 * @package App\Http\Controllers\Webhooks
 */
class ThrivecartController extends Controller
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var \Laravel\Cashier\Subscription
     */
    protected $subscription;

    /**
     * @var array
     */
    protected $events = [
        'order.success',
        'order.subscription_payment',
        'order.refund',
        'order.subscription_cancelled',
        'affiliate.commission_earned',
        'affiliate.commission_payout',
        'affiliate.commission_refund'
    ];

    /**
     * TrivecartController constructor.
     * @param Project $project
     * @param \Laravel\Cashier\Subscription $subscription
     */
    public function __construct(Project $project, \Laravel\Cashier\Subscription $subscription)
    {
        $this->project      = $project;
        $this->subscription = $subscription;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        $event = $request->input('event');
        $hash  = $request->input('thrivecart_secret');
        if ($hash != config('fubbi.thrivecart_key')) {
            Log::error('wrong hash: ' . $hash);
            return new Response('Unauthorized request', 200);
        }
        if (!in_array($event, $this->events)) {
            Log::error('wrong event: ' . $event);
            return new Response('Undefined event', 200);
        }
        $method = camel_case(str_replace('.', '_', $event));
        return method_exists($this, $method)
            ? call_user_func([$this, $method], $request)
            : new Response('Webhook Handled', 200);
    }

    /**
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function orderSuccess(Request $request, UserManager $userManager)
    {
        $product_id = $request->input('base_product');

        if (!$product_id or !isset(config('fubbi.thrive_cart_plans')[$product_id])) {
            Log::error('wrong product_id: ' . $product_id);
            return new Response('Webhook Handled', 200);
        }
        try {
            //stripe customer id
            $customer_identifier = $request->input('customer_identifier');
            //form data
            $customer            = $request->input('customer');
            //subscription ids, get first
            $subscription_id     = collect($request->input('subscriptions'))->first();
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

                }
//                $tmp_password = str_random(8);
//                $user         = User::create([
//                    'email'          => $customer['email'] ?? '',
//                    'first_name'     => $customer['first_name'] ?? '',
//                    'last_name'      => $customer['last_name'] ?? '',
//                    'password'       => Hash::make($tmp_password),
//                    'phone'          => $customer['contactno'] ?? '',
//                    'stripe_id'      => $stripe_subscription->customer,
//                    'trial_ends_at'  => Carbon::createFromTimestamp($stripe_subscription->trial_end),
//                    'card_brand'     => $customer_card->brand,
//                    'card_last_four' => $customer_card->last4,
//                ]);
//                $user->tmp_password = $tmp_password;
//                $user->save();
//                $role = Role::where('name', Role::CLIENT)->first();
//                $user->attachRole($role);
//                $user->setMeta('address_line_1', $customer_card->address_line1);
//                $user->setMeta('zip', $customer_card->address_zip);
//                $user->setMeta('city', $customer_card->address_city);
//                $user->setMeta('country', $customer_card->address_country);
//                $user->setMeta('state', $customer['address']['state'] ?? '');
//                $user->save();
            }

            $subscription              = $this->subscription;
            $subscription->user_id     = $user->id;
            $subscription->name        = $customer['business_name'] ?? str_random(10);
            $subscription->stripe_id   = $subscription_id;
            $subscription->stripe_plan = $plan_id;
            $subscription->quantity    = 1;

            $subscription->save();

            $project                  = $this->project;
            $project->client_id       = $user->id;
            $project->subscription_id = $subscription->id;
            $project->name            = $customer['business_name'] ?? 'Project #' . strval($request->input('order_id'));
            $project->state           = ProjectStates::QUIZ_FILLING;

            $project->save();
            $project->setServices($plan_id);
            $project->setCycle($plan_id);


        } catch (\Exception $e) {
            Log::error($e);
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function cartRedirect(Request $request)
    {
        //looking for data of a new client account created by thrivecart webhook handler
        $customer_data = $request->input('thrivecart');
        $email         = $customer_data['customer']['email'] ?? false;

        //if there is no email provided form thrivecart with redirect
        if (!$email) {
            Session::flash('change_password');
            return redirect()
                ->action('Auth\LoginController@login')
                ->with(
                    'error',
                    'Something wrong happened while redirecting, please find the password in your email inbox'
                );
        }

        //find new user, 5 attempts
        for ($i = 0; $i < 5; $i++) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                sleep(3); //wait 3 seconds before next attempt
            } else {
                break;
            }
        }

        //if user has not beed created by thrivecart webhook handler
        if (!$user) {
            Session::flash('change_password');
            return redirect()
                ->action('Auth\LoginController@login')
                ->with(
                    'error',
                    'Please, find the password in your email inbox'
                );
        }

        //re-login user
        Auth::logout();
        Auth::login($user, true);

        //in case user already has an account
        if ($user->projects()->count() > 1) {
            $project = $user->projects()->latest()->first();
            if ($project) {
                return redirect()->action('Resources\ProjectController@edit', [
                    $project,
                    's' => ProjectStates::QUIZ_FILLING
                ]);
            }
        }

        //in case user has to set the password
        Session::flash('change_password');

        //redirect to project filling page
        return redirect()->action('SettingsController@index')->with('success', 'Please create a new password');
    }
}
