<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
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
		        [
			        'name'         => 'admin',
			        'display_name' => 'Service administrator',
			        'description'  => 'Admin, has all possible permissions',
			        'created_at'   => \Carbon\Carbon::now(),
		        ],
		        [
			        'name'         => 'client',
			        'display_name' => 'Client',
			        'description'  => 'Client, the most important person here',
			        'created_at'   => \Carbon\Carbon::now(),
		        ],
		        [
			        'name'         => 'account_manager',
			        'display_name' => 'Account Manager',
			        'description'  => 'Account Manager, handles non-trivial actions',
			        'created_at'   => \Carbon\Carbon::now(),
		        ],
		        [
			        'name'         => 'writer',
			        'display_name' => 'Writer',
			        'description'  => 'Writer, creates text content',
			        'created_at'   => \Carbon\Carbon::now(),
		        ],
		        [
			        'name'         => 'editor',
			        'display_name' => 'Editor',
			        'description'  => 'Editor, edits created content',
			        'created_at'   => \Carbon\Carbon::now(),
		        ],
		        [
			        'name'         => 'designer',
			        'display_name' => 'Designer',
			        'description'  => 'Designer, creates appearance',
			        'created_at'   => \Carbon\Carbon::now(),
		        ],
		        [
			        'name'         => 'researcher',
			        'display_name' => 'Researcher',
			        'description'  => 'Researches content for project',
			        'created_at'   => \Carbon\Carbon::now(),
		        ]
	        ]
        );
    }
}
