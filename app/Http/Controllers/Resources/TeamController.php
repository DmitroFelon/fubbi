<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        switch ($user->role) {
            case 'admin':
                $teams = Team::with('users')->get();

                break;
            case 'client':
                $teams = $user->teams()->with('users')->get();
                if ($teams->isEmpty()) {
                    return redirect()->action('Resources\ProjectController@create');
                }
                break;
            default:
                $teams = $user->teams()->with('users')->get();

                break;
        }

        return view('entity.team.index', ['teams' => $teams]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Team $team)
    {
        $owner_users     = $team->getPossibleOwners();
        $invitable_users = $team->getInvitableUsers();
        return view('entity.team.create', compact('owner_users', 'invitable_users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $team)
    {
        $team->fill($request->except(['_token']));
        $team->save();


        if ($request->has('users')) {
            $users = User::whereIn('id', $request->input('users'));
            $users->each(function (User $user) use ($team) {
                if ($user->id == Auth::user()->id) {
                    return;
                }
                $user->inviteTo($team);
            });
        }

        return redirect()->action('Resources\TeamController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return view('entity.team.show', ['team' => $team]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        $owner_users     = $team->getPossibleOwners();
        $invitable_users = $team->getInvitableUsers($team);
        return view('entity.team.edit', compact('team', 'owner_users', 'invitable_users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Team $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $team->fill(
            $request->except(['_method', '_token'])
        );

        $team->save();

        if ($request->has('users')) {
            $ids   = array_keys($request->input('users'));
            $users = User::whereIn('id', $ids)->get();
            $users->each(function (User $user) use ($team) {
                $user->inviteTo($team);
            });
        }

        return redirect()->action('Resources\TeamController@index')->with('success', _('Users have been invited'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        //
    }

    /**
     * @param Team $team
     * @return mixed
     */
    public function accept(Team $team)
    {
        $user = Auth::user();

        $team->users()->attach($user->id);

        $invite = $user->getInviteToTeam($team->id);

        if (!$invite) {
            abort(403);
        }

        $invite->accept();

        return redirect()->back()->with('success', _i('Now You are a part of this team'));
    }

    /**
     * @param Team $team
     * @return mixed
     */
    public function decline(Team $team)
    {
        $user = Auth::user();

        $invite = $user->getInviteToTeam($team->id);

        if (!$invite) {
            abort(403);
        }

        $invite->decline();

        return redirect()->back()->with('info', _i('Invitation has been declined'));
    }
}
