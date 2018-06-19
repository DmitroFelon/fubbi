<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\Plan\PlanManager;
use App\Services\Plan\PlanRepository;
use Illuminate\Http\Request;
use Stripe\Plan;
use App\Services\Project\ProjectRepository;
use App\Services\Subscription\SubscriptionRepository;

/**
 * Class PlanController
 * @package App\Http\Controllers
 */
class PlanController extends Controller
{
    /**
     * @var PlanManager
     */
    protected $planManager;

    /**
     * @var PlanRepository
     */
    protected $planRepository;

    /**
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * @var SubscriptionRepository
     */
    protected $subscriptionRepository;

    /**
     * PlanController constructor.
     * @param PlanManager $planManager
     * @param PlanRepository $planRepository
     * @param ProjectRepository $projectRepository
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(
        PlanManager $planManager,
        PlanRepository $planRepository,
        ProjectRepository $projectRepository,
        SubscriptionRepository $subscriptionRepository

    )
    {
        $this->middleware('can:index,' . Plan::class)->only(['index', 'show', 'edit', 'update']);
        $this->planManager = $planManager;
        $this->planRepository = $planRepository;
        $this->projectRepository = $projectRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $plans = $this->planRepository->plans();

        return view('entity.plan.index', ['plans' => $plans]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $plan = $this->planRepository->planById($id);
        $data['nickname'] = $plan->nickname;
        $data['id'] = $plan->id;
        $data['meta'] = collect($plan->metadata->jsonSerialize());
        $data['projects'] = $this->projectRepository->projectsWhereSubscriptionIdsIn($this->subscriptionRepository->idByStripePlan($plan->id));

        return view('entity.plan.show', ['plan' => $data]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $plan = $this->planRepository->planById($id);
        $plan->meta = collect($plan->metadata->jsonSerialize());

        return view('entity.plan.edit', ['plan' => $plan]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $response = $this->planManager->update($this->planRepository->planById($id), collect($request->except(['_token', '_method'])));

        return $response->status == 'success'
            ? redirect()->action('Resources\PlanController@show', $id)->with($response->status, $response->message)
            : redirect()->back()->with($response->status, $response->message);
    }
}
