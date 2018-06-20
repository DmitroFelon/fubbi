<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Http\Requests\CreateIssueReportRequest;
use App\Services\Issue\IssueManager;

/**
 * Class IssueController
 * @package App\Http\Controllers\Resources
 */
class IssueController extends Controller
{
    /**
     * @var IssueManager
     */
    protected $issueManager;

    /**
     * IssueController constructor.
     * @param IssueManager $issueManager
     */
    public function __construct(IssueManager $issueManager)
    {
        $this->issueManager = $issueManager;
    }

    /**
     * @param CreateIssueReportRequest $request
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateIssueReportRequest $request, Issue $issue)
    {
        return
            redirect()
            ->route('issues.show', $this->issueManager->create($issue, $request->user(), $request->input()))
            ->with('success', _i('Issue created'));
    }

    /**
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Issue $issue)
    {
        $this->issueManager->update($issue);

        return
            redirect()
            ->route('issues.index')
            ->with('success', _i('Issue updated'));
    }

    /**
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Issue $issue)
    {
        $this->issueManager->delete($issue);

        return redirect()->route('issues.index')->with('success', _i('Issue removed'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('entity.issue.index', ['issues' => Issue::orderBy('state')->simplePaginate(15)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('entity.issue.create');
    }

    /**
     * @param Issue $issue
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Issue $issue)
    {
        return view('entity.issue.show', ['issue' => $issue]);
    }
}
