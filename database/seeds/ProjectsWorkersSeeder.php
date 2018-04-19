<?php

use Illuminate\Database\Seeder;

class ProjectsWorkersSeeder extends Seeder
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
                'project_id' => rand(1, 50),
                'user_id' => rand(1, 21)
            ];
        }

        DB::table('project_worker')->insert($rows);
    }
}
