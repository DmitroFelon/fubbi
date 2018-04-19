<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

/**
 * Adds modigicators to project's plan services
 *
 * Class PlanController
 *
 * @package App\Http\Controllers\Project
 */
class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        return $project->plan->id;
    }

    public function show(Project $project, $id)
    {
        return redirect()->action('Project\PlanController@edit', [$project, $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $plan_id = $project->subscription->stripe_plan;

        $services = $project->services;

        return view('entity.plan.project.edit', compact('plan_id', 'services', 'project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Models\Project $project
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, $id, Request $request)
    {

        try {
            collect($request->input())->each(function ($item, $key) use ($project) {
                $service = $project->services()->whereName($key)->first();
                if ($service) {
                    $service->customize(strval($item));
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Plan has been modified succesfully');
    }
}
