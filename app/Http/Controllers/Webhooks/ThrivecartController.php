<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use App\Services\Subscription\SubscriptionManager;
use App\Services\Thrivecart\ThrivecartManager;
use App\Services\User\UserManager;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
     * @param SubscriptionManager $subscriptionManager
     * @param ThrivecartManager $thrivecartManager
     * @return Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function orderSuccess(Request $request, UserManager $userManager, SubscriptionManager $subscriptionManager, ThrivecartManager $thrivecartManager)
    {
        $product_id = $request->input('base_product');
        if (!$product_id or !isset(config('fubbi.thrive_cart_plans')[$product_id])) {
            Log::error('wrong product_id: ' . $product_id);
            return new Response('Webhook Handled', 200);
        }
        try {
            $thrivecartManager->create($userManager, $subscriptionManager, $request->input(), $this->project);
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
