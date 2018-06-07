<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Plan\PlanManager;
use Illuminate\Http\Request;

/**
 * Adds modifiers to project's plan services
 *
 * Class PlanController
 *
 * @package App\Http\Controllers\Project
 */
class PlanController extends Controller
{
    /**
     * @param Project $project
     * @return mixed
     */
    public function index(Project $project)
    {
        return $project->plan->id;
    }

    /**
     * @param Project $project
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Project $project, $id)
    {
        return redirect()->action('Project\PlanController@edit', [$project, $id]);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Project $project)
    {
        $plan_id = $project->subscription->stripe_plan;
        $services = $project->services;
        return view('entity.plan.project.edit', compact('plan_id', 'services', 'project'));
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param PlanManager $planManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Project $project, Request $request, PlanManager $planManager)
    {
        try {
            $planManager->updateProjectPlan($request->input(), $project);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->back()->with('success', 'Plan has been modified successfully');
    }
}
