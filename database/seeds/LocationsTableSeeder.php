<?php

use Illuminate\Database\Seeder;
use Arrow\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create(['name' => '100/01-08-056-20W4/00', 'field_id' => 1, 'formation' => "Injection Site"]);
        Location::create(['name' => '100/15-07-056-20W4/00', 'field_id' => 1, 'formation' => "Injection Site"]);
        Location::create(['name' => '100/16-08-056-20W4/00', 'field_id' => 1, 'formation' => "Injection Site"]);
        Location::create(['name' => '102/01-08-056-20W4/00', 'field_id' => 2, 'formation' => "Injection Site"]);
        Location::create(['name' => '102/05-07-056-20W4/00', 'field_id' => 2, 'formation' => "Injection Site"]);
        Location::create(['name' => '102/10-06-056-20W4/00', 'field_id' => 2, 'formation' => "Injection Site"]);
        Location::create(['name' => '102/16-08-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '103/04-07-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '103/05-07-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '103/08-08-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '103/09-08-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '103/13-06-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '104/08-08-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
        Location::create(['name' => '104/09-08-056-20W4/00', 'field_id' => 3, 'formation' => "Injection Site"]);
    }
}
