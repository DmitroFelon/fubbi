<?php

namespace App\Http\Controllers\Webhooks;


use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;


/**
 * Class WebhookController
 * @package App\Http\Controllers
 */
class WebhookController extends CashierController
{
    /**
     * @param $payload
     * @return array
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        $subscription_id = $payload['data']['object']['subscription'] ?? false;
        throw_unless($subscription_id, \Exception::class, 'Subscription id is not set');
        $project = Project::where('subscription_id', $subscription_id)->first();

        if($project){
            $project->reset();
        }
        
        return ['Webhook Handled'];
    }

    /**
     * Hold project
     *
     * @param $payload
     * @return array
     * @throws \Throwable
     * @throws string
     */
    public function handleInvoicePaymentFailed($payload)
    {
        $subscription_id = $payload['data']['object']['subscription'] ?? false;
        throw_unless($subscription_id, \Exception::class, 'Subscription id is not set');
        $project = Project::where('subscription_id', $subscription_id)->first();
        $client  = $project->client;

        if ($client->subscription($project->name)) {
            $client->subscription($project->name)->cancel();
        }

        return ['Webhook Handled'];
    }

    /**
     * @param $payload
     * @return array
     */
    public function handleInvoicePaymentPending($payload)
    {
        return ['Webhook Handled'];
    }

    /**
     * @param $payload
     * @return array
     * @throws \Throwable
     * @throws string
     */
    public function handleInvoiceUpcoming($payload)
    {
        $subscription_id = $payload['data']['object']['subscription'] ?? false;

        throw_unless($subscription_id, \Exception::class, 'Subscription id is not set');

        $project = Project::where('subscription_id', $subscription_id)->first();

        if ($project) {
            $client = $project->client;
            $client->notify(new \App\Notifications\Project\Subscription($project));
        }

        return ['Webhook Handled'];
    }

    /**
     * @param $payload
     * @return array
     */
    public function handleInvoiceCreated($payload)
    {
        return ['Webhook Handled'];
    }
}
