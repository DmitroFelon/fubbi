<?php

use Illuminate\Database\Seeder;

class TeamsTableSeed extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		DB::table('teams')->insert(
			[
				[
					'name'        => 'writers',
					'description' => 'writers team',
				],
				[
					'name'        => 'designers',
					'description' => 'designers team',
				],
			]

		);
	}
}
