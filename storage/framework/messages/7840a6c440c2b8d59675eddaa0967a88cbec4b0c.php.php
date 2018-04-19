<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Subscription;
use Stripe\Plan;

class PlanController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$data = [];

		$plans = Cache::remember(
			'public_plans',
			60,
			function () {

				$filtered_plans = Collection::make();

				foreach (Plan::all()->data as $plan) {
					$filtered_plans->push($plan);
				}

				return $filtered_plans;
			}
		);

		$plans->each(
			function (Plan $plan, $i) {
				$plan->meta     = $plan->metadata->jsonSerialize();
				$plan->projects = Subscription::where('stripe_plan', $plan->id)->count();
			}
		);

		$data['plans'] = $plans;

		return view('entity.plan.index', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$data = [];

		$plan = Plan::retrieve($id);

		$plan->meta = collect($plan->metadata->jsonSerialize());

		$plan->projects = Project::whereIn(
			'subscription_id',
			Subscription::select('id')->where('stripe_plan', $plan->id)->get()
		)->get();

		$data['plan'] = $plan;

		return view('entity.plan.show', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$data = [];

		$plan = Plan::retrieve($id);

		$plan->meta = collect($plan->metadata->jsonSerialize());

		$data['plan'] = $plan;

		return view('entity.plan.edit', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

		$input = collect($request->except(['_token', '_method']));

		$input->transform(
			function ($item, $key) {
				if ($item == 'true' or $item == false) {
					return ($item == 'true') ? true : false;
				}

				return $item;
			}
		);

		$plan           = Plan::retrieve($id);
		$plan->metadata = $input->toArray();
		$plan->save();

		return redirect()->action('PlanController@show', $id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
