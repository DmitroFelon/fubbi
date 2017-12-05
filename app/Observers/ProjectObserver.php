<?php

namespace App\Observers;

use App\Models\Project;
use App\Observers\Traits\Project\Keywords;
use App\Observers\Traits\Project\States;
use App\Observers\Traits\Project\Workers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectObserver
{
	use Keywords, Workers, States;

	protected $user;

	protected $request;

	public function __construct(Request $request)
	{
		$this->user    = Auth::user();
		$this->request = $request;
	}

	public function created(Project $project)
	{
		$should_be_notified = [
			'admin' => \App\Notifications\Project\Created::class,
		];

		$should_be_invited = [
			'account_manager',
		];

		$this->user->notify(new \App\Notifications\Project\Created($project));

		foreach ($should_be_notified as $role => $model) {
			$users = User::withRole($role)->get();
			$users->each(
				function (User $user, $key) use ($project, $model) {
					$user->notify(new $model($project));
				}
			);
		}

		foreach ($should_be_invited as $role) {
			$users = User::withRole($role)->get();
			$users->each(
				function (User $user, $key) use ($project) {
					$user->inviteTo($project);
				}
			);
		}
	}

	public function filled(Project $project)
	{
		$should_be_invited = [
			'account_manager',
			'writer',
			'editor',
			'designer',
		];

		foreach ($should_be_invited as $role) {
			$users = User::withRole($role)->get();
			$users->each(
				function (User $user, $key) use ($project) {
					$user->inviteTo($project);
				}
			);
		}
	}
}