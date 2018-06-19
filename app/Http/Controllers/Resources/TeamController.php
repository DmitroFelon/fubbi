<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Team\TeamManager;
use App\Services\Team\TeamRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOrUpdateTeamRequest;

/**
 * Class TeamController
 * @package App\Http\Controllers
 */
class TeamController extends Controller
{
    /**
     * @var TeamManager
     */
    protected $teamManager;

    /**
     * @var TeamRepository
     */
    protected $teamRepository;

    /**
     * TeamController constructor.
     * @param TeamManager $teamManager
     * @param TeamRepository $teamRepository
     */
    public function __construct(
        TeamManager $teamManager,
        TeamRepository $teamRepository
    )
    {
        $this->middleware('can:index,' . Team::class)->only(['index']);
        $this->middleware('can:show,team')->only(['show']);
        $this->middleware('can:create,' . Team::class)->only(['create', 'store']);
        $this->middleware('can:delete,team')->only(['destroy', 'update', 'edit']);
        $this->teamManager = $teamManager;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param CreateOrUpdateTeamRequest $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateOrUpdateTeamRequest $request, Team $team)
    {
        $this->teamManager->create($team, $request->input());

        return redirect()->route('teams.index')->with('success', 'New team has been successfully created.');
    }

    /**
     * @param CreateOrUpdateTeamRequest $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CreateOrUpdateTeamRequest $request, Team $team)
    {
        $this->teamManager->update($team, $request->input());

        return redirect()->route('teams.index')->with('success', _('Team has been successfully updated.'));
    }

    /**
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Team $team)
    {
        $this->teamManager->delete($team);

        return redirect()->route('teams.index')->with('success', _i('Team has been successfully deleted.'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('entity.team.index', ['teams' => $this->teamRepository->teams(Auth::user())]);
    }

    /**
     * @param Team $team
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Team $team)
    {
        return view('entity.team.create', [
            'owner_users' => $team->getPossibleOwners(),
            'invitable_users' => $team->getInvitableUsers()
        ]);
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
        return view('entity.team.edit', [
            'team' => $team,
            'owner_users' => $team->getPossibleOwners(),
            'invitable_users' => $team->getInvitableUsers($team)
        ]);
    }
}
