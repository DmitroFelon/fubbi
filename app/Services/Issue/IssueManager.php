<?php

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
     * @throws \Exception
     */
    public function delete(Issue $issue)
    {
        $issue->delete();
    }

    /**
     * @param Issue $issue
     */
    public function update(Issue $issue)
    {
        $issue->update(['state' => Issue::STATE_FIXED]);
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