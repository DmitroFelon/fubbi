<?php

namespace Laravel\Cashier\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        /*if (! $this->isInTestingEnvironment() && ! $this->eventExistsOnStripe($payload['id'])) {
            return;
        }*/

        $method = 'handle' . studly_case(str_replace('.', '_', $payload['type']));

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        } else {
            return $this->missingMethod();
        }
    }

    protected function handleCustomerSubscriptionCreated(array $payload)
    {
        return new Response('Webhook Handled', 200);
        if (!isset($payload['data']['object']['customer'])) {
            return new Response('Webhook Handled', 200);
        }

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        $subscription_id  = $payload['data']['object']['id'];
        $cur_period_start = $payload['data']['object']['current_period_start'];
        $cur_period_end   = $payload['data']['object']['current_period_end'];
        $plan_id          = $payload['data']['object']['plan']['id'];

        $meta = $payload['data']['object']['metadata'];
        if (isset($meta['customer_id'])) {
            $thrivecart_customer_id = $meta['customer_id'];
        }

        if (!$user) {
            /*$user = User::create([

            ]);*/
        }
    }

    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {

        if (!isset($payload['data']['object']['customer'])) {
            return new Response('Webhook Handled', 200);
        }

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if ($user) {
            $user->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
                $subscription->markAsCancelled();
            });
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Get the billable entity instance by Stripe ID.
     *
     * @param  string $stripeId
     * @return \Laravel\Cashier\Billable
     */
    protected function getUserByStripeId($stripeId)
    {
        $model = getenv('STRIPE_MODEL') ?: config('services.stripe.model');

        return (new $model)->where('stripe_id', $stripeId)->first();
    }

    /**
     * Verify with Stripe that the event is genuine.
     *
     * @param  string $id
     * @return bool
     */
    protected function eventExistsOnStripe($id)
    {
        try {
            return !is_null(StripeEvent::retrieve($id, config('services.stripe.secret')));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Verify if cashier is in the testing environment.
     *
     * @return bool
     */
    protected function isInTestingEnvironment()
    {
        return getenv('CASHIER_ENV') === 'testing';
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function missingMethod($parameters = [])
    {
        return new Response;
    }
}
