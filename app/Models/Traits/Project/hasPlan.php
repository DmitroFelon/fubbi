<?php

namespace App\Models\Traits\Project;

use Illuminate\Support\Facades\Cache;
use Stripe\Plan;

/**
 * Class hasPlan
 *
 * @package App\Models\Traits\Project
 */
trait hasPlan
{
    use hasModifications;

    /**
     * @param $value
     * @return mixed
     *
     *
     * Get Plan from Stripe api
     */
    public function getPlanAttribute($value)
    {
        return Cache::remember('stripe_plan_' . $this->subscription->stripe_plan, 10, function () {
            try {
                return $this->plan = Plan::retrieve($this->subscription->stripe_plan);
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    /**
     * @param $value
     * @return array|mixed
     *
     * Get metadata from Stripe Plan
     * Also save to project meta
     */
    public function getPlanMetadataAttribute($value)
    {
        $project = $this;

        $result = Cache::remember('plan_matadata_' . $this->id, 60, function () use ($project) {

            try {
                if (isset($this->plan) and !is_null($this->plan)) {
                    $metadata = (isset($this->plan->metadata) and method_exists($this->plan->metadata, 'jsonSerialize'))
                        ? $this->plan->metadata->jsonSerialize()
                        : null;
                } else {
                    $plan     = Plan::retrieve($this->subscription->stripe_plan);
                    $metadata = (isset($plan->metadata) and method_exists($plan->metadata, 'jsonSerialize'))
                        ? $plan->metadata->jsonSerialize()
                        : null;
                }

                $metadata = collect($metadata);

            } catch (\Exception $e) {
                return null;
            }

            return $metadata->transform(function ($item, $key) use ($project) {
                if ($this->isModified($key)) {
                    return $item;
                } else {
                    $project->modify($key, $item, false);
                    return $item;
                }
            });

        });

        $project->save();

        return $result;
    }

    /**
     * @param $service
     *
     * Get each metadata as Project service
     */
    public function getServiceName(string $service)
    {
        $plan_services = [
            'articles_count'     => _i('Articles Count'),
            'articles_words'     => _i('Articles Words'),
            'facebook_posts'     => _i('Facebook Posts'),
            'instagram_posts'    => _i('Instagram Posts'),
            'twitter_posts'      => _i('Twitter Posts'),
            'pinterest_posts'    => _i('Pinterest Posts'),
            'linkedin_posts'     => _i('Linkedin Posts'),
            'marketing_calendar' => _i('Marketing Calendar'),
            'linkedin_articles'  => _i('Linkedin Articles'),
            'slideshare'         => _i('Slideshare'),
            'medium'             => _i('Medium'),
            'quora'              => _i('Quora'),
        ];

        return $plan_services[$service] ?? 'service';
    }

    

    /**
     * @return array
     */
    public function getVariableServices()
    {
        return [
            'facebook_posts',
            'instagram_posts',
            'twitter_posts',
            'pinterest_posts',
            'linkedin_posts',
            'linkedin_articles',
            'slideshare',
            'medium',
            'quora',
        ];
    }

    /**
     * @return array
     */
    public function getCountableServices()
    {
        return [
            'facebook_posts',
            'instagram_posts',
            'twitter_posts',
            'pinterest_posts',
            'linkedin_posts',
            'linkedin_articles',
            'slideshare',
            'medium',
            'quora',
        ];
    }

    /**
     * @param $service
     * @return mixed
     *
     * Get existed enity of service for current project
     */
    public function getServiceResult(string $service)
    {
        return $this->articles()->withAnyTags([$service], 'service_type')->get();
    }

    /**
     * @param string $service
     */
    public function isRequireService(string $service)
    {

        $metadata = $this->plan_metadata;

        $result = $metadata->get($service);

        $require = ($result == false or $result == 'false' or $result == null)
            ? false
            : true;

        return $require;

    }
}