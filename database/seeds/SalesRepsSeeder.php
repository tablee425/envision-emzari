<?php

use Illuminate\Database\Seeder;

use Arrow\SalesRep;

class SalesRepsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesRep::create(['user_id' => 17, 'code' => 100]);
        SalesRep::create(['user_id' => 20, 'code' => 101]);
        SalesRep::create(['user_id' => 18, 'code' => 103]);
        SalesRep::create(['user_id' => 19, 'code' => 104]);
        SalesRep::create(['user_id' => 51, 'code' => 105]);
        SalesRep::create(['user_id' => 66, 'code' => 106]);
        SalesRep::create(['user_id' => 4, 'code' => 198]);
        SalesRep::create(['user_id' => 4, 'code' => 199]);
        SalesRep::create(['user_id' => 4, 'code' => 200]);
        SalesRep::create(['user_id' => 4, 'code' => 202]);
        SalesRep::create(['user_id' => 16, 'code' => 301]);
        SalesRep::create(['user_id' => 63, 'code' => 302]);
    }
}
