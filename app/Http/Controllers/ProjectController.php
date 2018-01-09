<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProject;
use App\Models\Article;
use App\Models\Helpers\ProjectStates;
use App\Models\Keyword;
use App\Models\Project;
use App\Models\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Kodeine\Metable\MetaData;
use Spatie\MediaLibrary\Media;
use Stripe\Error\InvalidRequest;
use Stripe\Plan;
use Stripe\Subscription;

/**
 * Class ProjectController
 *
 * @package App\Http\Controllers
 */
class ProjectController extends Controller
{
    /**
     * ProjectController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('can:index,' . Project::class)->only(['index', 'show']);
        $this->middleware('can:create,' . Project::class)->only(['create', 'store']);
        $this->middleware('can:update,project')->only(['edit', 'update']);
        $this->middleware('can:delete,project')->only(['delete', 'destroy', 'resume']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        switch ($user->role) {
            case 'admin':
                $projects = Project::query();

                if ($request->has('status') and $request->get('status')) {
                    if ($request->get('status') == 'active') {
                        $projects = $projects->whereHas('subscription', function ($query) {
                            $query->whereNull('ends_at');
                        });
                    } else {
                        $projects = $projects->whereHas('subscription', function ($query) {
                            $query->whereNotNull('ends_at');
                        });
                    }
                }

                if ($request->has('user') and $request->get('user')) {
                    $projects = $projects->where('client_id', $request->get('user'));
                }

                if ($request->has('month') and $request->get('month')) {
                    $projects = $projects->where('created_at', '>=', Carbon::now()->subMonth($request->get('month')));
                }

                $projects = $projects->paginate(10);
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

        $filters = [];

        $filters['users'] = User::without(['notifications', 'invites'])->withRole('client')
                                ->get(['id', 'first_name', 'last_name', 'email']);
        //prepare and filter users for the view
        $clients          = $filters['users']->keyBy('id')->transform(function (User $user) {
            return $user->name;
        });
        $filters['users'] = $clients->filter()->put('', _i('All clients'))->reverse()->toArray();


        $filters['months'] = [
            ''   => _('All time'),
            '1'  => _('1 month'),
            '3'  => _('3 months'),
            '6'  => _('6 months'),
            '12' => _('12 months'),
        ];

        $filters['status'] = [
            ''         => _i('Any status'),
            'active'   => _i('Active'),
            'deactive' => _i('Deactive'),

        ];

        return view('entity.project.index', ['projects' => $projects, 'filters' => $filters]);
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
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {

        $keywords           = $project->keywords;
        $keywords_questions = $project->keywords_questions;
        $metadata           = $project->metaToView();
        $manager            = $project->workers()->withRole('account_manager')->first(['id']);
        $manager_id         = ($manager) ? $manager->id : null;


        $users = [];
        $teams = [];

        if (Auth::user()->role == 'admin' or ($manager_id and $manager_id == Auth::user()->id)) {
            //get users which are not attached to this project

            $users = $project->getAvailableWorkers();
            $teams = Team::all();
        }

        return view('entity.project.show', compact('project', 'keywords', 'keywords_questions', 'metadata', 'users', 'teams'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Request $request)
    {
        $step = ($request->has('s'))
            ? $request->input('s')
            : $project->state;


        $keywords = $project->getMeta('keywords');

        $articles = $project->articles;

        return view('entity.project.edit', compact('keywords', 'articles', 'project', 'step'));
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
        if (!$request->has('_step')) {
            abort(404);
        }

        $project = $project->filling($request);

        return redirect()->action('ProjectController@edit', [$project]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        try {
            $client = $project->client;

            if ($client->subscription($project->name)) {
                $client->subscription($project->name)->cancel();
            }
        } catch (InvalidRequest $e) {
            $project->forceDelete();
        }


        return redirect()->action('ProjectController@index');
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resume(Project $project)
    {
        $client = $project->client;

        if ($client->subscription($project->name)) {
            $client->subscription($project->name)->resume();
        }

        return redirect()->action('ProjectController@index');
    }

    /**
     * Accept project and start working
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept_review(Project $project)
    {
        $project->setState(\App\Models\Helpers\ProjectStates::ACCEPTED_BY_MANAGER);

        return redirect()->action('ProjectController@show', [$project]);
    }

    /**
     * Reject project
     *
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject_review(Project $project)
    {
        $project->setState(\App\Models\Helpers\ProjectStates::REJECTED_BY_MANAGER);

        return redirect()->action('ProjectController@show', [$project]);
    }

    /**
     * Attach worker to the project
     *
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply_to_project(Project $project, Request $request)
    {
        $message_key = 'info';
        $user        = $request->user();

        if ($project->hasWorker($user->role) or $project->teams->isNotEmpty()) {
            $message_key = 'error';
            $message     = _i('You are too late. This project already has %s', [$user->role]);
        } else {
            $project->attachWorker($user->id);
            $invite = $user->getInviteToProject($project->id);
            if (!$invite) {
                abort(403);
            }
            $invite->accept();
            $message = _i('You are applied to this project');
        }

        return redirect()->action('ProjectController@show', [$project])->with($message_key, $message);
    }

    /**
     * Reject worker from the project
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decline_project(Project $project, Request $request)
    {
        $message_key = 'info';
        $message     = _i('You are declined this project');
        $user        = $request->user();

        $invite = $user->getInviteToProject($project->id);
        if (!$invite) {
            abort(403);
        }
        $invite->decline();

        return redirect()->action('ProjectController@show', [$project])->with($message_key, $message);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_stored_files(Project $project, Request $request)
    {
        if (!$request->has('collection')) {
            return Response::json('error', 400);
        }

        $files = $project->getMedia($request->get('collection'));

        $files->transform(function (Media $media) {
            try {
                $media->url = $media->getFullUrl('dropzone');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $media->url = $media->getFullUrl();
            }
            return $media;
        });

        return Response::json($files->filter()->toArray(), 200);
    }

    /**
     * @param Project $project
     * @param Request $request
     */
    public function remove_stored_files(Project $project, Media $media, Request $request)
    {
        $project->media->find($media->id)->delete();

        return Response::json('success', 200);
    }

    /**
     * Save not completed project
     *
     * @param Project $project
     * @param Request $request
     * @return array
     */
    public function prefill(Project $project, Request $request)
    {
        try {
            $project->prefill($request);
            return Response::json(['success', 'error' => false], 200);
        } catch (\Exception $e) {
            return Response::json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function prefill_files(Project $project, Request $request)
    {
        $files = $project->addFiles($request);

         $files->transform(function (Media $media) {
            try {
                $media->url = $media->getFullUrl('dropzone');
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $media->url = $media->getFullUrl();
            }
            return $media;
        });

        return Response::json($files, 200);
    }

    /**
     * Export all project's data to zip
     *
     * @param Project $project
     * @return mixed
     */
    public function export(Project $project)
    {
        return response()->download($project->export())->deleteFileAfterSend(true);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invite_users(Project $project, Request $request)
    {
        if ($request->has('users')) {
            $project->attachWorkers(array_keys($request->input('users')));

            $attached_users = User::whereIn('id', array_keys($request->input('users')))->get();

            $attached_users_names = implode(', ', $attached_users->pluck('name')->toArray());

            return redirect()->back()->with(
                'info',
                _i('Users: %s have been sucessfully attached to project: "%s"', [$attached_users_names, $project->name])
            );
        }


        return redirect()->back()->with('error', _i('Users were not specified'));
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invite_team(Project $project, Request $request)
    {
        if ($request->has('team')) {

            $team = Team::findOrFail($request->input('team'));

            $project->attachTeam($request->input('team'));

            return redirect()->back()->with(
                'info',
                _i('Team: "%s" have been sucessfully attached to project: "%s"', [$team->name, $project->name])
            );
        }

        return redirect()->back()->with('error', _i('Team was not specified'));
    }
}
