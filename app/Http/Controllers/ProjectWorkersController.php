<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\User\UserRepository;
use App\Services\ProjectParticipants\Interfaces\ParticipantTeamInterface;
use App\Services\ProjectParticipants\Interfaces\ParticipantUserInterface;

/**
 * Class ProjectWorkersController
 * @package App\Http\Controllers
 */
class ProjectWorkersController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ParticipantUserInterface
     */
    protected $participantUser;

    /**
     * @var ParticipantTeamInterface
     */
    protected $participantTeam;

    /**
     * ProjectWorkersController constructor.
     * @param UserRepository $userRepository
     * @param ParticipantUserInterface $participantUser
     * @param ParticipantTeamInterface $participantTeam
     */
    public function __construct(
        UserRepository $userRepository,
        ParticipantUserInterface $participantUser,
        ParticipantTeamInterface $participantTeam
    )
    {
        $this->userRepository = $userRepository;
        $this->participantUser = $participantUser;
        $this->participantTeam = $participantTeam;
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachWorkers(Request $request, Project $project)
    {
        $id = array_keys($request->input('users'));
        if ($request->has('users')) {
            $this->participantUser->attach($project, $id);

        return count($this->userRepository->findByIds($id)) == 1
            ? redirect()->back()->with('success', 'New participant has been successfully added to this project!')
            : redirect()->back()->with('success', 'New participants have been successfully added to this project!');
        }

        return redirect()->back()->with('error', _i('You have to choose at least one user!'));
    }

    /**
     * @param Project $project
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detachWorkers(Project $project, $id)
    {
        $this->participantUser->detach($project, $id);

        return redirect()->back()->with('success', 'Worker has been successfully removed from this project!');
    }

    /**
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attachTeam(Request $request, Project $project)
    {
        if ($request->has('team')) {
            $this->participantTeam->attach($project, (array) $request->input('team'));

            return redirect()->back()->with('success', 'New team has been successfully added to this project!');
        }

        return redirect()->back()->with('error', _i('You have to choose team!'));
    }

    /**
     * @param Project $project
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function detachTeam(Project $project, $id)
    {
        $this->participantTeam->detach($project, $id);

        return redirect()->back()->with('success', 'Team has been successfully removed from this project!');
    }
}
