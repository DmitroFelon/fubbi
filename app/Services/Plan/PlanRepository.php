<?php

namespace App\Services\Plan;

use App\Services\Project\ProjectRepository;
use App\Services\Subscription\SubscriptionRepository;
use Illuminate\Support\Facades\Cache;
use Stripe\Plan;

/**
 * Class PlanRepository
 * @package App\Services\Plan
 */
class PlanRepository
{
    /**
     * @var ProjectRepository
     */
    protected $projectRepository;

    /**
     * @var SubscriptionRepository
     */
    protected $subscriptionRepository;

    /**
     * PlanRepository constructor.
     * @param ProjectRepository $projectRepository
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(
        ProjectRepository $projectRepository,
        SubscriptionRepository $subscriptionRepository
    )
    {
        $this->projectRepository = $projectRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @return mixed
     */
    public function plans()
    {
        $plans = Cache::remember('public_plans', 1440, function () {
            $filtered_plans = collect();
            foreach ($this->subscriptionRepository->allPlans() as $plan) {
                $filtered_plans->push($plan);
            }

            return $filtered_plans;
        });

        return $plans;
    }

    /**
     * @param $id
     * @return Plan
     */
    public function planById($id)
    {
        return Plan::retrieve($id);
    }
}