<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\User\UserManager;
use App\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserSettingsRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserController
 * @package App\Http\Controllers\Resources
 */
class UserController extends Controller
{
    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->middleware('can:index,' . User::class)->only(['index', 'destroy', 'create', 'store']);
        $this->middleware('can:show,' . User::class)->only(['show']);
        $this->middleware('can:update,' . User::class)->only(['edit', 'update']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::withTrashed()->get();

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
     * @throws \Psr\SimpleCache\InvalidArgumentException
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
        $targetUser = User::withTrashed()->findOrFail($id);

        return view('entity.user.show', ['user' => $targetUser]);
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
     * @param User $user
     * @param UserManager $userManager
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user, UserManager $userManager)
    {
        return $userManager->blockOrRestore($user->id, Auth::user()->id);
    }
}
