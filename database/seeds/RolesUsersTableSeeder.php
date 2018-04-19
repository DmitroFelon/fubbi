<?php

use Illuminate\Database\Seeder;

class RolesUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [];
        for ($i = 0; $i < 20; $i++) {
            $rows[] = [
                'role_id' => rand(1, 6),
                'user_id' => rand(1, 21)
            ];
        }

        DB::table('role_user')->insert($rows);
    }
}
