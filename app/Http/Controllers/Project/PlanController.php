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
     * @var PlanManager
     */
    protected $planManager;

    /**
     * PlanController constructor.
     * @param PlanManager $planManager
     */
    public function __construct(PlanManager $planManager)
    {
        $this->planManager = $planManager;
    }

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Project $project, Request $request)
    {
        $this->planManager->updateProjectPlan($request->input(), $project);

        return redirect()->back()->with('success', 'Plan has been modified successfully');
    }
}
