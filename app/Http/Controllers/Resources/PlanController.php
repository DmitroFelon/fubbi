<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\Plan\PlanManager;
use App\Services\Plan\PlanRepository;
use Illuminate\Http\Request;
use Stripe\Plan;

/**
 * Class PlanController
 * @package App\Http\Controllers
 */
class PlanController extends Controller
{
    /**
     * PlanController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:index,' . Plan::class)->only(['index']);
        $this->middleware('can:show,' . Plan::class)->only(['show']);
        $this->middleware('can:update,' . Plan::class)->only(['edit', 'update']);
    }

    /**
     * @param PlanRepository $planRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(PlanRepository $planRepository)
    {
        $plans = $planRepository->plans();
        return view('entity.plan.index', ['plans' => $plans]);
    }

    /**
     * @param $id
     * @param PlanRepository $planRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id, PlanRepository $planRepository)
    {
        $plan = $planRepository->plan($id);
        return view('entity.plan.show', ['plan' => $plan]);
    }

    /**
     * @param $id
     * @param PlanRepository $planRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, PlanRepository $planRepository)
    {
        $plan = $planRepository->updatePlan($id);
        return view('entity.plan.edit', ['plan' => $plan]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param PlanManager $planManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id, PlanManager $planManager)
    {
        try {
            $planManager->update(Plan::retrieve($id), collect($request->except(['_token', '_method'])));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'You have to add metadata to plan!');
        }
        return redirect()->action('Resources\PlanController@show', $id)->with('success', 'Plan has been modified successfully');
    }
}
