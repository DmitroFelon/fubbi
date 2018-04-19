<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\FlashMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
	public function subscribe(Request $request, Project $project)
	{
		try{
			//create subscription
			$subscription = Auth::user()
				->newSubscription($request->input('project_name'), $request->input('plan_id'))
				->create($request->input('stripeToken'));
			//attach client id
			$project->client_id = Auth::user()->id;
			//set name
			$project->name = $request->input('project_name');
			//attach subscription
			$project->subscription_id = $subscription->id;
			//set state
			$project->setState(\App\Models\Helpers\ProjectStates::QUIZ_FILLING);

			$project->save();

			FlashMessage::make(__('Your subscribtion created successfully'), FlashMessage::SUCCESS);

			return redirect()->action('ProjectController@edit', [$project]);

		}catch (\Exception $e){
			dd($e);
		}
	}
}
