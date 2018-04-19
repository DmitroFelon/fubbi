<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 09/11/17
 * Time: 12:33
 */

namespace App\Models\Traits\Project;

use App\Exceptions\Project\ImpossibleProjectState;
use App\Facades\ProjectExport;
use App\Models\Helpers\ProjectStates;
use App\Models\Idea;
use App\Models\Keyword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;

/**
 * Class hasStates
 *
 * @package App\Models\Traits\Project
 */
trait hasStates
{
    /**
     * @return bool
     */
    public function isOnReview()
    {
        $reviewable_states = [
            ProjectStates::MANAGER_REVIEW,
            ProjectStates::REJECTED_BY_MANAGER
        ];

        return in_array($this->state, $reviewable_states);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return $this
     */
    public function filling(Request $request)
    {
        switch ($request->input('_step')) {
            case ProjectStates::QUIZ_FILLING:
                $this->fillQuiz($request);
                break;
            case ProjectStates::KEYWORDS_FILLING:
                $this->fillKeywords($request);
                break;
            default:
                abort(404);
                break;
        }

        $this->save();

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function fillQuiz(Request $request)
    {
        $this->setMeta(
            $request->except(
                [
                    '_token',
                    '_project_id',
                    '_step',
                    '_method',
                    'compliance_guideline',
                    'logo',
                    'article_images',
                    'ready_content',
                    'themes',
                    'themes_order',
                    'questions'
                ]
            )
        );
        $this->addFiles($request);
        $this->setState(\App\Models\Helpers\ProjectStates::KEYWORDS_FILLING);
    }

    /**
     * @param string $state
     * @return \App\Models\Project
     * @throws \Exception
     */
    public function setState($state)
    {
        throw_unless(
            $this->validateState($state),
            ImpossibleProjectState::class,
            _i('Impossible state of project')
        );

        $this->state = $state;

        if ($this->isDirty()) {
            $this->save();
            $this->fireModelEvent('setState', false);
        }

        return $this;
    }

    /**
     * @param $state
     * @return bool
     */
    public function validateState($state)
    {
        return in_array($state, ProjectStates::$states);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function fillKeywords(Request $request)
    {
        $this->setState(ProjectStates::MANAGER_REVIEW);
        $this->filled();
    }

    /**
     * Fires model event "filled"
     * Called after client fill all necessary data
     * and project ready for manager review
     */
    public function filled()
    {
        //TODO check project state if project filled, send events to workers
        $this->fireModelEvent('filled', false);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function prefill(Request $request)
    {

        try {
            if ($request->input('_step') == ProjectStates::QUIZ_FILLING) {
                return $this->prefillQuiz($request);
            }

            if ($request->input('_step') == ProjectStates::KEYWORDS_FILLING) {
                return $this->prefillKeywords($request);
            }
        } catch (\Exception $e) {
            throw $e;
        }

        throw new Exception('Undefined project step: ' . $request->input('_step'));
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function prefillKeywords(Request $request)
    {
        $keywords           = collect($request->input('keywords'));
        $keywords_questions = collect($request->input('keywords_questions'));
        $meta               = collect($request->input('meta'));


        $meta->each(function ($metadata, $idea_id) use ($request) {

            $metafields = [
                'points_covered',
                'points_avoid',
                'references',
                'this_month',
                'next_month',
            ];

            $idea = Idea::findOrFail($idea_id);

            foreach ($metafields as $metafield) {
                $idea->{$metafield} = $metadata[$metafield] ?? null;
            }

            $idea->save();
        });

        $keywords->each(function ($keyword_texts, $idea_id) use ($request) {
            Idea::findOrFail($idea_id)->keywords()->suggestions()->update(
                ['accepted' => false]
            );

            if (!is_array($keyword_texts)) {
                return;
            }

            foreach ($keyword_texts as $keyword_text => $state) {
                Keyword::updateOrCreate(
                    ['idea_id' => $idea_id, 'text' => $keyword_text],
                    [
                        'accepted' => true,
                        'text'     => $keyword_text,
                        'idea_id'  => $idea_id,
                        'type'     => Keyword::TYPE_SUGGESTION
                    ]
                );
            }

        });
        $keywords_questions->each(function ($keyword_texts, $idea_id) {
            Idea::findOrFail($idea_id)->keywords()->questions()->update(
                ['accepted' => false]
            );

            if (!is_array($keyword_texts)) {
                return;
            }

            foreach ($keyword_texts as $keyword_text => $state) {
                Keyword::updateOrCreate(
                    ['idea_id' => $idea_id, 'text' => $keyword_text],
                    [
                        'accepted' => true,
                        'text'     => $keyword_text,
                        'idea_id'  => $idea_id,
                        'type'     => Keyword::TYPE_QUESTION
                    ]
                );
            }

        });
        return true;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function prefillQuiz(Request $request)
    {
        $this->setMeta(
            $request->except(
                [
                    '_token',
                    '_project_id',
                    '_step',
                    '_method',
                    'compliance_guideline',
                    'logo',
                    'article_images',
                    'ready_content',
                    'themes',
                    'themes_order',
                    'questions'
                ]
            )
        );

        $themes = ($request->has('themes_order'))
            ? $request->input('themes_order')
            : $request->input('themes');

        $themes = collect($themes);

        if ($themes->isNotEmpty()) {
            $themes->each(function ($item) {
                $idea = $this->ideas()->updateOrCreate(
                    ['theme' => $item],
                    ['theme' => $item, 'type' => Idea::TYPE_THEME]
                );
                return $idea;
            });
        }

        $questions = $request->input('questions');

        $questions = collect($questions);

        if ($questions->isNotEmpty()) {
            $questions->each(function ($item, $key) {
                $idea = $this->ideas()->updateOrCreate(
                    ['theme' => $item, 'type' => Idea::TYPE_QUESTION],
                    ['theme' => $item, 'type' => Idea::TYPE_QUESTION]
                );
                return $idea;
            });
        }

        $this->save();

        return true;
    }

    /**
     * @return float|int
     */
    public function getProgress()
    {

        $service = $this->services()->where('name', 'articles_count')->first();
        $cycle   = $this->cycles()->latest('id')->first();

        if (!$service or !$cycle) {
            return 0;
        }

        $require_articles        = intval($service->value);
        $total_articles_accepted = $this->articles()->accepted()->where('cycle_id', $cycle->id)->count();

        return ($require_articles > 0)
            ? $total_articles_accepted / $require_articles * 100 : 0;
    }

    /**
     * Fired at the beginning of the new billing cycle
     *
     * resets atciles, asks to re-fill quiz and keywords
     *
     */
    public function reset()
    {
        $this->created_at = Carbon::now();
        $this->updated_at = Carbon::now();
        $this->unsetMeta('export');
        $this->save();
        $this->setCycle();
        $this->fireModelEvent('reset', false);
    }

    /**
     *
     */
    public function suspend()
    {
        try {
            $client = $this->client;
            if ($client->subscription($this->name)) {
                $client->subscription($this->name)->cancel();
                $this->fireModelEvent('suspend', false);
            }
        } catch (\Exception $e) {
            $this->forceDelete();
        }
    }

}