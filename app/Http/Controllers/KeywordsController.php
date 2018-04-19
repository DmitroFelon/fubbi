<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Keyword;
use App\Models\Project;
use App\Services\Api\Keywords\KeywordsFactoryInterface;
use Illuminate\Support\Facades\Session;

/**
 * Class KeywordsController
 *
 * @package App\Http\Controllers
 */
class KeywordsController extends Controller
{
    /**
     * @param \App\Models\Project $project
     * @param Idea $idea
     * @param \App\Services\Api\Keywords\KeywordsFactoryInterface $api
     * @return string
     */
    public function index(Project $project, Idea $idea, KeywordsFactoryInterface $api)
    {
        $max_count = config('fubbi.keywords_count');

        try {

            if ($idea->keywords()->suggestions()->get()->isEmpty()) {
                $keywords = $api->suggestions($idea->theme);

                $keywords_to_be_saved = collect();

                if ($keywords->count() > $max_count) {
                    $keywords = $keywords->shuffle()->take($max_count);
                }

                $keywords->each(function ($item) use ($keywords_to_be_saved) {
                    $keywords_to_be_saved->push(
                        ['text' => $item, 'accepted' => false, 'type' => Keyword::TYPE_SUGGESTION]
                    );
                });

                $keywords_to_be_saved = $keywords_to_be_saved->toArray();

                $keywords = $idea->keywords()->createMany($keywords_to_be_saved);

            } else {
                $keywords = $idea->keywords()->suggestions()->get();
            }

            if ($idea->keywords()->questions()->get()->isEmpty()) {
                $keywords_questions = $api->questions($idea->theme);

                $keywords_to_be_saved = collect();

                if ($keywords_questions->count() > $max_count) {
                    $keywords_questions = $keywords_questions->shuffle()->take($max_count);
                }

                $keywords_questions->each(function ($item) use ($keywords_to_be_saved) {
                    $keywords_to_be_saved->push(
                        ['text' => $item, 'accepted' => false, 'type' => Keyword::TYPE_QUESTION]
                    );
                });

                $keywords_to_be_saved = $keywords_to_be_saved->toArray();

                $keywords_questions = $idea->keywords()->createMany($keywords_to_be_saved);

            } else {
                $keywords_questions = $idea->keywords()->questions()->get();
            }


            return view('entity.project.partials.form.keywords-step', compact('project', 'keywords', 'keywords_questions', 'idea'));
        } catch (\Exception $e) {
            return '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
        }
    }
}
