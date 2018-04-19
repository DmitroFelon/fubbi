<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProject;
use App\Models\Article;
use App\Models\Keyword;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Stripe\Plan;

/**
 * Class ProjectController
 *
 * @package App\Http\Controllers
 */
class ProjectController extends Controller
{
	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * ProjectController constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->authorizeResource(Project::class);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = $this->request->user();

		switch ($user->role) {
			case 'admin':
				$projects = Project::paginate(10);
				break;
			case 'client':
				$projects = $user->projects()->paginate(10);
				if ($projects->isEmpty()) {
					return redirect()->action('ProjectController@create');
				}
				break;
			default:
				$projects = $user->projects()->paginate(10);
				break;
		}

		return view('entity.project.index', ['projects' => $projects]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$step = 'plan';

		$public_plans = Cache::remember(
			'public_plans',
			60,
			function () {
				$available_plans = [
					'fubbi-basic-plan',
					'fubbi-bronze-plan',
					'fubbi-silver-plan',
					'fubbi-gold-plan',
				];

				$filtered_plans = Collection::make();

				foreach (Plan::all()->data as $plan) {
					if (in_array($plan->id, $available_plans)) {
						$filtered_plans->push($plan);
					}
				}

				return $filtered_plans->reverse();
			}
		);

		return view(
			'entity.project.create',
			[
				'plans' => $public_plans,
				'step'  => $step,
			]
		);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \App\Http\Requests\StoreProject|\Illuminate\Http\Request $request
	 * @param \App\Models\Project $project
	 * @return \Illuminate\Http\Response
	 * @throws \Exception
	 * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
	 * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
	 */
	public function store(StoreProject $request, Project $project)
	{
		return;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\Models\Project $project
	 * @return \Illuminate\Http\Response
	 */
	public function show(Project $project)
	{
		$meta_to_cast = [
			'themes',
			'questions',
			'avoid_keywords',
			'article_images_links',
			'image_pages',
			'google_access',
		];

		$meta_to_skip = [
			'themes_order',
		];

		$project->metas->transform(
			function ($item, $key) use ($meta_to_cast, $meta_to_skip) {

				$item->value = (filter_var(
					$item->value,
					FILTER_VALIDATE_URL
				)) ? '<a href="'.$item->value.'">'.$item->value.'</a>' : $item->value;

				if (in_array($item->key, $meta_to_cast)) {
					$item->value = explode(',', $item->value);
				}

				return (in_array($item->key, $meta_to_skip)) ? null : $item;
			}
		);

		$data = [
			'project' => $project,
		];

		return view('entity.project.show', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param \App\Models\Project $project
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Project $project)
	{
		return view(
			'entity.project.edit',
			[
				'keywords' => $project->getMeta('keywords'),
				'articles' => Article::all(),
				'project'  => $project,
				'step'     => $project->state,
			]
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \App\Http\Requests\StoreProject|\Illuminate\Http\Request $request
	 * @param \App\Models\Project $project
	 * @return \Illuminate\Http\Response
	 */
	public function update(StoreProject $request, Project $project)
	{

		if (! $request->has('_step')) {
			abort(404);
		}

		$project = $project->filling($request);

		return redirect()->action('ProjectController@edit', [$project]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		return redirect()->action('ProjectController@index');
	}

	public function accept_review(Project $project)
	{
		$project->setState(\App\Models\Helpers\ProjectStates::ACCEPTED_BY_MANAGER);

		return redirect()->action('ProjectController@show', [$project]);
	}

	public function reject_review(Project $project)
	{
		$project->setState(\App\Models\Helpers\ProjectStates::REJECTED_BY_MANAGER);

		return redirect()->action('ProjectController@show', [$project]);
	}

	public function apply_to_project(Project $project)
	{
		$message_key = 'info';
		$user        = $this->request->user();

		if ($project->hasWorker($user->role) or $project->teams->isNotEmpty()) {
			$message_key = 'error';
			$message     = __('You are too late. This project already has %s', $user->role);
		} else {
			$project->attachWorker($user->id);
			$invite = $user->getInviteToProject($project->id);
			$invite->accept();
			$message = __('You are applied to this project');
		}

		return redirect()->action('ProjectController@show', [$project])->with($message_key, $message);
	}

	public function decline_project(Project $project)
	{
		$message_key = 'info';
		$message     = __('You are declined this project');
		$user        = $this->request->user();

		$invite = $user->getInviteToProject($project->id);
		$invite->decline();

		return redirect()->action('ProjectController@show', [$project])->with($message_key, $message);
	}

	public function prefill(Project $project, Request $request)
	{
		$keywords = $project->prefill($request);

		return ['result' => $keywords];
	}
}
