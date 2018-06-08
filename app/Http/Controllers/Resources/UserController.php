<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Project;
use App\Services\User\UserManager;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserSettingsRequest;

/**
 * Class UserController
 * @package App\Http\Controllers\Resources
 */
class UserController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->middleware('can:index,' . User::class)->only(['index']);
        $this->middleware('can:update,user')->only(['edit', 'update']);
        $this->middleware('can:user.apply_to_project,')->only(['apply_to_project']);
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = $this->user->withTrashed()->get();
        return view('entity.user.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('entity.user.create');
    }

    /**
     * @param CreateUserRequest $request
     * @param User $user
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request, User $user, UserManager $userManager)
    {
        try {
            $userManager->userCreate($user, $request->input());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something wrong happened while user creation. Try again, please.');
        }
        return redirect()->action('Resources\UserController@index')->with('success', 'User has been created successfully');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = $this->user->withTrashed()->findOrFail($id);
        return view('entity.user.show', ['user' => $user]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('entity.user.edit');
    }

    /**
     * @param UpdateUserSettingsRequest $request
     * @param User $user
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserSettingsRequest $request, User $user, UserManager $userManager)
    {
        return $userManager->userUpdate($user, $request->input());
    }

    /**
     * @param $id
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, UserManager $userManager)
    {
        return $userManager->blockOrRestore($id, Auth::user()->id);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decline_project(Project $project, Request $request, UserManager $userManager)
    {
        return $userManager->declineProjectInvite($request->user(), $request->input(), $project);
    }

    /**
     * @param Project $project
     * @param Request $request
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply_to_project(Project $project, Request $request, UserManager $userManager)
    {
        $data = $userManager->acceptProjectInvite($project, $request->user());
        if($data['access'] = 0) {
            return redirect()->back()->with('error', "You can't perform this action");
        }
        return redirect()->action('Resources\ProjectController@show', $project)->with($data['message_key'], $data['message']);
    }

    /**
     * @param Team $team
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptTeamInvite(Team $team, UserManager $userManager)
    {
        $userManager->acceptTeamInvite(Auth::user(), $team);
        return redirect()->back()->with('success', _i('Now You are a part of this team'));
    }

    /**
     * @param Team $team
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function declineTeamInvite(Team $team, UserManager $userManager)
    {
        $userManager->declineTeamInvite(Auth::user(), $team);
        return redirect()->back()->with('info', _i('Invitation has been declined'));
    }
}
