<?php

namespace App\Http\Controllers;

use App\Services\Api\Keywords\KeywordsFactoryInterface;
use App\Services\Api\KeywordTool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ResearchController extends Controller
{


    public function index()
    {
        return view('entity.research.index');
    }

    /**
     * @param \App\Services\Api\Keywords\KeywordsFactoryInterface $api
     * @return string
     */
    public function load(Request $request, KeywordsFactoryInterface $api)
    {

        $theme = ($request->has('theme'))
            ? trim($request->input('theme'))
            : null;

        $source = ($request->has('source'))
            ? trim($request->input('source'))
            : KeywordTool::SOURCE_GOOGLE;

        $country = ($request->has('country'))
            ? $request->input('country')
            : 'au';

        $language = 'en';

        if (strlen($country) > 2) {
            $countries = config('fubbi.countries', []);
            $key       = array_search($country, $countries);
            $country   = ($key)
                ? $key
                : 'au';
        }

        $metrics = 'googlesearchnetwork';

        $key = implode('.', [
            $theme,
            $country,
            $language,
            $metrics,
            $source,
        ]);

        $remember = Carbon::MINUTES_PER_HOUR * Carbon::HOURS_PER_DAY;

        $questions = Cache::remember('questions.' . $key, $remember,
            function () use ($api, $theme, $country, $language, $metrics, $source) {
                try {
                    return $api->questions($theme, $country, $language, $metrics, $source);
                } catch (\Exception $e) {
                    return collect($e->getMessage());
                }
            });

        $suggestions = Cache::remember('suggestions.' . $key, $remember,
            function () use ($api, $theme, $country, $language, $metrics, $source) {
                try {
                    return $api->suggestions($theme, $country, $language, $metrics, $source);
                } catch (\Exception $e) {
                    return collect($e->getMessage());
                }
            });

        $title = KeywordTool::getSourceName($source);

        return view('entity.research.partials.result', compact('title', 'questions', 'suggestions'));
    }
}
