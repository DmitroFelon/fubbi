<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Team\TeamManager;
use App\Services\Team\TeamRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOrUpdateTeamRequest;

/**
 * Class TeamController
 * @package App\Http\Controllers
 */
class TeamController extends Controller
{
    /**
     * ProjectController constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware('can:index,' . Team::class)->only(['index']);
        $this->middleware('can:show,team')->only(['show']);
        $this->middleware('can:create,' . Team::class)->only(['create', 'store']);
        $this->middleware('can:delete,team')->only(['destroy']);

    }

    /**
     * @param TeamRepository $teamRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TeamRepository $teamRepository)
    {
        $user = Auth::user();
        $teams = $teamRepository->teams($user);
        if($teams->isEmpty() && $user->role == 'client') {
            return redirect()->action('Resources\InspirationController@index');
        }
        else {
            return view('entity.team.index', ['teams' => $teams]);
        }
    }

    /**
     * @param Team $team
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Team $team)
    {
        $owner_users     = $team->getPossibleOwners();
        $invitable_users = $team->getInvitableUsers();
        return view('entity.team.create', compact('owner_users', 'invitable_users'));
    }

    /**
     * @param CreateOrUpdateTeamRequest $request
     * @param Team $team
     * @param TeamManager $teamManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateOrUpdateTeamRequest $request, Team $team, TeamManager $teamManager)
    {
        $teamManager->createTeam($team, $request->input());
        return redirect()->action('Resources\TeamController@index');
    }

    /**
     * @param Team $team
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Team $team)
    {
        return view('entity.team.show', ['team' => $team]);
    }

    /**
     * @param Team $team
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Team $team)
    {
        $owner_users     = $team->getPossibleOwners();
        $invitable_users = $team->getInvitableUsers($team);
        return view('entity.team.edit', compact('team', 'owner_users', 'invitable_users'));
    }

    /**
     * @param CreateOrUpdateTeamRequest $request
     * @param Team $team
     * @param TeamManager $teamManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateOrUpdateTeamRequest $request, Team $team, TeamManager $teamManager)
    {
        $teamManager->updateTeam($team, $request->input());
        return redirect()->action('Resources\TeamController@index')->with('success', _('Users have been invited'));
    }

    /**
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect(action('Resources\TeamController@index'))->with('success', _i('Team removed'));
    }
}
