<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Outline;
use App\Models\Project;
use Illuminate\Http\Request;

class OutlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Project $project )
    {
        return $project->outlines;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Project $project )
    {
        return $project;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, Outline $outline, Request $request)
    {
        return $project;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @param $outline_id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, $outline_id )
    {
       return $project->outlines()->findOrFail($outline_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Outline $outline)
    {
        return $outline;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, Outline $outline, Request $request)
    {
        return $outline;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outline  $outline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Outline $outline)
    {
        return $outline;
    }
}
