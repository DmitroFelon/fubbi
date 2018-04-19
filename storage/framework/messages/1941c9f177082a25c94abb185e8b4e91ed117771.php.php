<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * ProjectController constructor.
	 *
	 * @param \Illuminate\Http\Request $request
	 */
	public function __construct(Request $request)
	{

		$this->request = $request;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		//get roles

		$roles = Cache::remember(
			'role_names',
			60 * 60 * 60,
			function () {
				return Role::all();
			}
		);

		//process search
		if ($this->request->input('s')) {
			$users = User::search($this->request->input('s'))->get();
		} else {
			$users = User::all();
		}

		$search_suggestions = Cache::remember(
			'user_search_suggestions',
			60 * 60 * 60,
			function () {
				$search_suggestions = collect();
				User::all()->map(
					function (User $user) use ($search_suggestions) {
						$search_suggestions->push($user->name);
						$search_suggestions->push($user->email);
					}
				);
				$search_suggestions = $search_suggestions->toArray();

				return '["'.implode('", "', $search_suggestions).'"]';
			}
		);

		//separate users by roles
		$groupedByRoles = $users->groupBy('role');

		//fill emprty roles by emprty collection
		foreach ($roles as $role) {
			$groupedByRoles[$role->name] = (isset($groupedByRoles[$role->name])) ? $groupedByRoles[$role->name] : collect(
			);
		}

		return view('entity.user.index', compact(['users', 'groupedByRoles', 'roles', 'search_suggestions']));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('entity.user.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param \App\User $user
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		return view('entity.user.show', ['user' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view('entity.user.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}
}
