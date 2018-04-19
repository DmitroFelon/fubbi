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
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Project $project, $id = null)
	{
		$data = $this->output($project);

		return view('entity.plan.project.edit', $data);
	}

	private function output(Project $project)
	{
		$plan           = $project->plan;
		$plan->metadata = collect($plan->metadata);

		$plan->metadata->transform(
			function ($item, $key) use ($project) {
				$modif = $project->getModified($key);
				return ($project->isModified($key)) ? $modif : $item;
			}
		);

		return compact(['plan', 'project']);
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
		$plan = $project->plan;

		$plan->metadata = collect($plan->metadata);

		$input_metadata = collect($request->except(['_method', '_token']));

		$diff = $plan->metadata->diffAssoc($input_metadata);

		//check true false values 
		$diff->each(
			function ($item, $key) use ($project, $input_metadata) {
				$project->modify(
					$key,
					$input_metadata->get($key),
					false
				);
			}
		);

		unset($project->plan);

		$project->save();

		$data = $this->output($project);

		return view('entity.plan.project.edit', $data);
	}
}
