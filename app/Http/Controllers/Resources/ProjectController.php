<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProject;
use App\Models\Helpers\ProjectStates;
use App\Models\Idea;
use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Services\Idea\IdeaRepository;
use App\Services\Project\ProjectManager;
use App\Services\Project\ProjectRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProjectController
 *
 * @package App\Http\Controllers
 */
class ProjectController extends Controller
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * ProjectController constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->middleware('can:index,' . Project::class)->only(['index']);
        $this->middleware('can:project.show,project')->only([
            'show',
            'get_stored_files',
            'remove_stored_files',
            'export'
        ]);
        $this->middleware('can:create,' . Project::class)->only(['create', 'store']);
        $this->middleware('can:update,project')->only(['edit', 'update']);
        $this->middleware('can:delete,project')->only(['delete', 'destroy', 'resume']);
        $this->middleware('can:project.accept-review,project')->only(['acceptReview', 'rejectReview']);
        $this->middleware('can:project.invite,project')->only(['attachUsers', 'attachTeam']);
    }

    /**
     * @param Request $request
     * @param ProjectRepository $projectRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request, ProjectRepository $projectRepository)
    {
        $user = Auth::user();
        if ($user->projects->count() == 1) {
            return redirect()->action('Resources\ProjectController@show', $user->projects->first());
        }
        $data =$projectRepository->projects($user, $request->input());
        $projects = $data['projects'];
        $filters = $data['filters'];
        $searchSuggestions = $data['searchSuggestions'];
        return view('entity.project.index', compact('projects', 'filters', 'searchSuggestions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
/*        if (Session::has('quiz')) {
            return redirect()
                ->action('Resources\ProjectController@edit', [Session::get('quiz'), 's' => ProjectStates::QUIZ_FILLING])
                ->with('info', _i('Please, complete the quiz'));
        }

        $step = ProjectStates::PLAN_SELECTION;

        $plans = Cache::remember(
            'public_plans',
            Carbon::MINUTES_PER_HOUR * Carbon::HOURS_PER_DAY,
            function () {
                $available_plans = config('fubbi.plans');
                $filtered_plans  = Collection::make();
                foreach (Plan::all()->data as $plan) {
                    if (in_array($plan->id, $available_plans)) {
                        $filtered_plans->push($plan);
                    }
                }
                return $filtered_plans->reverse();
            }
        );

        header("Cache-Control: no-store, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

        return view('entity.project.create', compact('plans', 'step'));*/
        return back()->with('error', _i("Can't create the project. Something goes wrong with Stripe api."));
    }

    /**
     * @param Project $project
     * @param ProjectRepository $projectRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Project $project, ProjectRepository $projectRepository)
    {
        $manager    = $project->workers()->withRole(Role::ACCOUNT_MANAGER)->first();
        $manager_id = ($manager) ? $manager->id : null;
        if (Auth::user()->role == 'admin' or ($manager_id and $manager_id == Auth::user()->id)) {
            //get users which are not attached to this project
            $users = $project->getAvailableWorkers();
            $teams = Team::all();
        }
        $projectData = $projectRepository->collectProjectData($project);
        return view('entity.project.show', compact('project', 'users', 'teams', 'projectData'));
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Project $project, Request $request)
    {
        if (!$request->has('s')) {
            return redirect()->action('Resources\ProjectController@edit', [$project, 's' => $project->state]);
        }
        $step = $project->state;
        $articles = ($step == ProjectStates::QUIZ_FILLING)
            ? $project->articles
            : collect();
        return view('entity.project.edit', compact('articles', 'project', 'step'));
    }

    /**
     * @param StoreProject $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreProject $request, Project $project)
    {
        if (!$request->has('_step')) {
            abort(404);
        }
        $project = $project->filling($request);
        if ($project->state == ProjectStates::MANAGER_REVIEW) {
            return redirect()->action('Resources\ProjectController@show', $project)
                             ->with('success', _i('Thank You, our team is working on your content'));
        }
        return redirect()->action('Resources\ProjectController@edit', [$project, 's' => $project->state]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
/*        try {
             $client = $project->client;
             if ($client->subscription($project->name)) {
                 $client->subscription($project->name)->cancel();
             }
        } catch (InvalidRequest $e) {
             $project->forceDelete();
        }
        return redirect()->action('Resources\ProjectController@index');*/
        return back()->with('error', _i("Can't delete the project. Something goes wrong with Stripe api."));
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
        return redirect()->action('Resources\ProjectController@index');
    }

    /**
     * @param Project $project
     * @param ProjectManager $projectManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptReview(Project $project, ProjectManager $projectManager)
    {
        $projectManager->setState($project, ProjectStates::ACCEPTED_BY_MANAGER);
        return redirect()->action('Resources\ProjectController@show', [$project]);
    }

    /**
     * @param Project $project
     * @param ProjectManager $projectManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectReview(Project $project, ProjectManager $projectManager)
    {
        $projectManager->setState($project, ProjectStates::REJECTED_BY_MANAGER);
        return redirect()->action('Resources\ProjectController@show', [$project]);
    }

    /**
     * @param Project $project
     * @param User $user
     * @param ProjectManager $projectManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detachUser(Project $project, User $user, ProjectManager $projectManager)
    {
        $data = $projectManager->detachUsers($project, $user);
        return redirect()->back()->with($data['message_key'], $data['message']);
    }

    /**
     * @param Project $project
     * @param Team $team
     * @param ProjectManager $projectManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detachTeam(Project $project, Team $team, ProjectManager $projectManager)
    {
        $data = $projectManager->detachTeam($project, $team);
        return redirect()->back()->with($data['message_key'], $data['message']);
    }

    /**
     * @param Idea $idea
     * @param IdeaRepository $ideaRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_stored_idea_files(Idea $idea, IdeaRepository $ideaRepository)
    {
        $files = $ideaRepository->storedFiles($idea);
        return response()->json($files->filter()->toArray(), 200);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function prefill(Project $project, Request $request)
    {
        try {
            $project->prefill($request);
            return response()->json(['success', 'error' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Export all project's data to zip
     *
     * @param Project $project
     * @return mixed
     */
    public function export(Project $project)
    {
        try {
            return response()->download($project->export());
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Somethig wrong happened while requirements export. Please, try later.');
        }
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ProjectManager $projectManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachUsers(Project $project, Request $request, ProjectManager $projectManager)
    {
        if ($request->has('users')) {
            return $projectManager->attachUsers($project, $request->input());
        }
        return redirect()->back()->with('error', _i('Users were not specified'));
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param ProjectManager $projectManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachTeam(Project $project, Request $request, ProjectManager $projectManager)
    {
        if ($request->has('team')) {
            return $projectManager->attachTeam($project, $request->input());
        }
        return redirect()->back()->with('error', _i('Team was not specified'));
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function allow_modifications(Project $project)
    {
        $project->setState(ProjectStates::QUIZ_FILLING);
        return redirect()->back()->with('success', 'Project state set to "Quiz Filling"');
    }
}
