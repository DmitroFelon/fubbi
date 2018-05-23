<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 08/12/17
 * Time: 10:00
 */

namespace App\Models\Helpers;

use Illuminate\Support\Facades\View;

abstract class ProjectStates
{
    /**
     * @const string
     */
    const CREATED = 'created';

    /**
     * @const string
     */
    const PLAN_SELECTION = 'plan';

    /**
     * @const string
     */
    const QUIZ_FILLING = 'quiz';

    /**
     * @const string
     */
    const MANAGER_REVIEW = 'on_manager_review';

    /**
     * @const string
     */
    const PROCESSING = 'processing';

    /**
     * @const string
     */
    const CLIENT_REVIEW = 'on_client_review';

    /**
     * @const string
     */
    const ACCEPTED_BY_CLIENT = 'accepted_by_client';

    /**
     * @const string
     */
    const REJECTED_BY_CLIENT = 'rejected_by_client';

    /**
     * @const string
     */
    const COMPLETED = 'completed';

    /**
     * @const string
     */
    const ACCEPTED_BY_MANAGER = 'accepted_by_manager';

    /**
     * @const string
     */
    const REJECTED_BY_MANAGER = 'rejected_by_manager';

    /**
     * @const string
     */
    const FILLING_BY_RESEARCHER = 'filling_by_researcher';

    /**
     * @var string[]
     */
    public static $states = [
        self::CREATED,
        self::PLAN_SELECTION,
        self::QUIZ_FILLING,
        self::MANAGER_REVIEW,
        self::ACCEPTED_BY_MANAGER,
        self::REJECTED_BY_MANAGER,
        self::FILLING_BY_RESEARCHER,
        self::PROCESSING,
        self::CLIENT_REVIEW,
        self::ACCEPTED_BY_CLIENT,
        self::REJECTED_BY_CLIENT,
        self::COMPLETED,
    ];

    public static $client_blocking_states = [
        self::ACCEPTED_BY_MANAGER,
        self::REJECTED_BY_MANAGER,
        self::PROCESSING,
        self::MANAGER_REVIEW,
    ];

    public static $changeable_states = [
        self::PLAN_SELECTION,
        self::QUIZ_FILLING,
    ];

    public static function getTab($state)
    {
        return View::exists('entity.project.tabs.' . $state)
            ? 'entity.project.tabs.' . $state
            : 'entity.project.tabs.blocked';
    }
}
