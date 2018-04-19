<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();
        $rows = [];
        for ($i = 0; $i < 50; $i++) {
            $rows[] = [
                'client_id' => rand(1, 21),
                'name' => $faker->company,
                'description' => $faker->text(200),
                'state' => ''
            ];
        }

        DB::table('projects')->insert($rows);
    }
}
