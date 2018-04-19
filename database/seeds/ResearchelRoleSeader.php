<?php

use Illuminate\Database\Seeder;

class ResearchelRoleSeader extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('roles')->insert(
			[
				'name'         => 'researcher',
				'display_name' => 'Researcher',
				'description'  => 'Researches content for project',
				'created_at'   => \Carbon\Carbon::now(),
			]
		);
	}
}
