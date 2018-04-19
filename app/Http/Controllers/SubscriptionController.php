<?php

namespace App\Http\Controllers;

use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SubscriptionController extends Controller
{

    public function __invoke(Request $request, Project $project)
    {
        try {
            $plan_id = $request->input('plan_id');

            //create subscription
            $subscription = Auth::user()
                                ->newSubscription(
                                    $request->input('project_name'),
                                    $plan_id)
                                ->create($request->input('stripeToken'));
            //attach client id
            $project->client_id = Auth::user()->id;
            //set name
            $project->name = $request->input('project_name');
            //attach subscription
            $project->subscription_id = $subscription->id;
            //set state
            $project->setState(ProjectStates::QUIZ_FILLING);
            $project->save();

            //fill services
            $project->setServices($plan_id);
            //set filrst cycle
            $project->setCycle($plan_id);

            //set quiz flag in case user will click "back" button
            Session::put('quiz', $project->id);

            return redirect()
                ->action('Resources\ProjectController@edit', [$project, 's' => ProjectStates::QUIZ_FILLING])
                ->with('success', _i('Your subscribtion has been created successfully'));

        } catch (\Exception $e) {
            redirect()->back()
                      ->with('error', _i('Something wrong happened while your subscription, please contact to administrator'));
        }
    }

}
