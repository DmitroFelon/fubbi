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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('entity.issue.index', ['issues' => Issue::orderBy('state')->simplePaginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('entity.issue.create');
    }

    /**
     * @param CreateIssueReportRequest $request
     * @param Issue $issue
     * @param IssueManager $issueManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateIssueReportRequest $request, Issue $issue, IssueManager $issueManager)
    {
        return redirect(action(
            'Resources\IssueController@show',
            $issueManager->create($issue, $request->user(), $request->input())))
            ->with('success', _i('Issue created'));
    }

    /**
     * Display the specified resource.
     *
     * @param Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        return view('entity.issue.show', ['issue' => $issue]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function update(Issue $issue)
    {
        $issue->update(['state' => Issue::STATE_FIXED]);
        return redirect(action('Resources\IssueController@index'))->with('success', _i('Issue updated'));
    }

    /**
     * @param Issue $issue
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect(action('Resources\IssueController@index'))->with('success', _i('Issue removed'));
    }
}
