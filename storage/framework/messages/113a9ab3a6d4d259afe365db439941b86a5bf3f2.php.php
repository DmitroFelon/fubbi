<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Project;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param \App\Models\Project $project
	 * @return \Illuminate\Http\Response
	 */
	public function index(Project $project)
	{
		return $project->articles;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \App\Models\Project $project
	 * @param \App\Models\Article $article
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Project $project, Article $article, Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Project $project
	 * @param  \App\Models\Article $article
	 * @return \Illuminate\Http\Response
	 */
	public function show(Project $project, Article $article)
	{
		return $article;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Project $project
	 * @param  \App\Models\Article $article
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Project $project, Article $article)
	{
		return $article;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \App\Models\Project $project
	 * @param  \App\Models\Article $article
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function update(Project $project, Article $article, Request $request)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param \App\Models\Project $project
	 * @param  \App\Models\Article $article
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Project $project, Article $article)
	{
		//
	}
}
