<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Services\User\UserManager;
use App\User;
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
}
