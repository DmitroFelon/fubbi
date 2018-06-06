<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.06.18
 * Time: 14:45
 */

namespace App\Services\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;


/**
 * Class SearchSuggestions
 * @package App\Services\Project
 */
class SearchSuggestions
{

    /**
     * @return mixed
     */
    public static function get()
    {
        Cache::forget('project_name_search_suggestions');
        return Cache::remember('project_name_search_suggestions', 60, function () {
            $search_suggestions = collect();
            Project::all()->map(function(Project $project) use ($search_suggestions) {
                $search_suggestions->push($project->name);
            });
            return $search_suggestions->toArray();
        });
    }

    /**
     * @return mixed
     */
    public static function toView()
    {
        return self::get();
    }
}