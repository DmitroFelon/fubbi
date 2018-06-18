<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\Invitations\Interfaces\TeamInviteInterface;
use App\Services\Invitations\Interfaces\ProjectInviteInterface;

/**
 * Class InviteController
 * @package App\Http\Controllers
 */
class InviteController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * InviteController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Team $team
     * @param TeamInviteInterface $teamInvite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptTeamInvite(Team $team, TeamInviteInterface $teamInvite)
    {
        $response = $teamInvite->accept($this->request->user(), $team);

        return redirect()->back()->with($response->status, _i($response->message));
    }

    /**
     * @param Team $team
     * @param TeamInviteInterface $teamInvite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function declineTeamInvite(Team $team, TeamInviteInterface $teamInvite)
    {
        $response = $teamInvite->decline($this->request->user(), $team);

        return redirect()->route('dashboard')->with($response->status, _i($response->message));
    }

    /**
     * @param Project $project
     * @param ProjectInviteInterface $projectInvite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptProjectInvite(Project $project, ProjectInviteInterface $projectInvite)
    {
        $response = $projectInvite->accept($this->request->user(), $project);

        return redirect()->back()->with($response->status, _i($response->message));
    }

    /**
     * @param Project $project
     * @param ProjectInviteInterface $projectInvite
     * @return \Illuminate\Http\RedirectResponse
     */
    public function declineProjectInvite(Project $project, ProjectInviteInterface $projectInvite)
    {
        $response = $projectInvite->decline($this->request->user(), $project);

        return redirect()->route('dashboard')->with($response->status, _i($response->message));
    }
}
