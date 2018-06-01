<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 12:17
 */

namespace App\Services\Issue;

use App\Models\Issue;
use App\User;

/**
 * Class IssueManager
 * @package App\Services\Issue
 */
class IssueManager
{
    /**
     * @param Issue $issue
     * @param User $user
     * @param array $params
     * @return Issue
     */
    public function create(Issue $issue, User $user, array $params)
    {
        $issue->fill($params);
        $issue->user_id = $user->id;
        $issue->state = Issue::STATE_CREATED;
        $issue->save();
        $issue = $this->attachTags($issue, $params);
        return $issue;
    }

    /**
     * @param Issue $issue
     * @param array $params
     * @return Issue
     */
    protected function attachTags(Issue $issue, array $params)
    {
        $tags = collect(explode(',', $params['tags']));
        $tags->each(function ($tag) use ($issue) {
            $issue->attachTagsHelper($tag);
        });
        $issue->save();
        return $issue;
    }
}