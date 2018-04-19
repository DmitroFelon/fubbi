<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(RolesTableSeeder::class);
        $this->call(UserSeeder::class);
        //$this->call(ResearchelRoleSeader::class);

        //factory(\App\Models\Article::class, 20)->create();

    }
}
