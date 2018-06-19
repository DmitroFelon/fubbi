<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\User\UserManager;
use App\Services\User\UserRepository;
use App\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Helpers\ProjectStates;

/**
 * Class UserController
 * @package App\Http\Controllers\Resources
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * UserController constructor.
     * @param User $user
     * @param UserRepository $userRepository
     * @param UserManager $userManager
     */
    public function __construct(
        User $user,
        UserManager $userManager,
        UserRepository $userRepository
    )
    {
        $this->userManager = $userManager;
        $this->userRepository = $userRepository;
        $this->middleware('can:show,' . User::class)->only(['show']);
        $this->middleware('can:update,' . User::class)->only(['edit', 'update']);
        $this->middleware('can:index,' . User::class)->only(['index', 'destroy', 'create', 'store']);
    }

    /**
     * @param CreateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request, User $user)
    {
        $this->userManager->create($user, $request->input());

        return redirect()->route('users.index')->with('success', 'User has been created successfully');
    }

    /**
     * @param UpdateUserSettingsRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserSettingsRequest $request, User $user)
    {
        $response = $this->userManager->update($user, $request->input());
        $user->load('projects');

        return $request->has('redirect_to_last_project') && $user->projects()->latest('id')->first()
            ? redirect()
                    ->route('projects.edit', [
                        $user->projects()->latest('id')->first(),
                        's' => ProjectStates::QUIZ_FILLING
                    ])
                    ->with('success', _i('Please, fill the quiz.'))
            : redirect()->back()->with($response->status, $response->message);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        $response = $this->userManager->blockOrRestore($user->id, Auth::user()->id);

        return redirect()->back()->with($response->status, $response->message);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('entity.user.index', ['users' => $this->userRepository->getAllUsers()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('entity.user.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('entity.user.show', ['user' => $this->userRepository->findById($id)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        return view('entity.user.edit');
    }
}
