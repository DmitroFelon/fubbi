<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'first_name' => 'Imad',
                    'last_name'  => 'Bazzal',
                    'phone'      => '123456789',
                    'email'      => 'imad.bazzal.93@gmail.com',
                    'password'   => Hash::make('8734969091'),
                ],
                [
                    'first_name' => 'First',
                    'last_name'  => 'Client',
                    'phone'      => '123456789',
                    'email'      => 'client@example.com',
                    'password'   => Hash::make('secret'),
                ],
                [
                    'first_name' => 'First',
                    'last_name'  => 'Writer',
                    'phone'      => '123456789',
                    'email'      => 'writer@example.com',
                    'password'   => Hash::make('secret'),
                ],
                [
                    'first_name' => 'First',
                    'last_name'  => 'Desiger',
                    'phone'      => '123456789',
                    'email'      => 'designer@example.com',
                    'password'   => Hash::make('secret'),
                ],
                [
                    'first_name' => 'First',
                    'last_name'  => 'Manager',
                    'phone'      => '123456789',
                    'email'      => 'manager@example.com',
                    'password'   => Hash::make('secret'),
                ],
                [
                    'first_name' => 'First',
                    'last_name'  => 'Editor',
                    'phone'      => '123456789',
                    'email'      => 'editor@example.com',
                    'password'   => Hash::make('secret'),
                ],
                [
                    'first_name' => 'First',
                    'last_name'  => 'Researcher',
                    'phone'      => '123456789',
                    'email'      => 'researcher@example.com',
                    'password'   => Hash::make('secret'),
                ],
            ]
        );

        App\User::where('email', 'imad.bazzal.93@gmail.com')->first()->roles()->attach(1);
        App\User::where('email', 'client@example.com')->first()->roles()->attach(2);
        App\User::where('email', 'manager@example.com')->first()->roles()->attach(3);
        App\User::where('email', 'writer@example.com')->first()->roles()->attach(4);
        App\User::where('email', 'editor@example.com')->first()->roles()->attach(5);
        App\User::where('email', 'designer@example.com')->first()->roles()->attach(6);
        App\User::where('email', 'researcher@example.com')->first()->roles()->attach(7);
    }
}
