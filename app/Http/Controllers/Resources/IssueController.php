<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issues = Issue::orderBy('state')->simplePaginate(15);

        return view('entity.issue.index', ['issues' => $issues]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Issue $issue)
    {
        $issue->fill($request->all());

        $issue->user_id = Auth::user()->id;

        $issue->state = Issue::STATE_CREATED;

        $issue->save();

        $tags = collect(explode(',', $request->input('tags')));

        $tags->each(function ($tag) use ($issue) {
            $issue->attachTagsHelper($tag);
        });

        $issue->save();

        return redirect(action('Resources\IssueController@show', $issue))->with('success', _i('Issue created'));
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
     * @param  \Illuminate\Http\Request $request
     * @param Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Issue $issue)
    {
        $issue->state = Issue::STATE_FIXED;
        $issue->save();

        return redirect(action('Resources\IssueController@index'))->with('success', _i('Issue updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Issue $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect(action('Resources\IssueController@index'))->with('success', _i('Issue removed'));
    }
}
