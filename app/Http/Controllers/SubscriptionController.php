<?php

namespace App\Http\Controllers;

use App\Models\Helpers\ProjectStates;
use App\Models\Project;
use App\Services\Subscription\SubscriptionManager;
use Illuminate\Http\Request;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class SubscriptionController extends Controller
{

    /**
     * @param Request $request
     * @param Project $project
     * @param SubscriptionManager $subscriptionManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, Project $project, SubscriptionManager $subscriptionManager)
    {
        $data = $subscriptionManager->subscriptionCreate($request->user(), $project, $request->input(), ProjectStates::QUIZ_FILLING);
        if($data['error'] != '') {
            return redirect()->back()->with('error', $data['error']);
        }
        return redirect()
            ->action('Resources\ProjectController@edit', [$data['project'], 's' => ProjectStates::QUIZ_FILLING])
            ->with('success', _i('Your subscription has been created successfully'));
    }
}
