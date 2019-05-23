<?php

use Illuminate\Database\Seeder;
use Arrow\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create(['email' => 'admin@envision.com', 'name' => 'Admin', 'admin' => 1, 'password' =>'password']);
        $user->companies()->attach([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]);

        $client = User::create(['email' => 'client@envision.com', 'name' => 'Client', 'password' => 'password']);
        $client->companies()->attach(1);
    }
}
