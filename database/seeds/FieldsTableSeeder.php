<?php

use Illuminate\Database\Seeder;
use Arrow\Field;

class FieldsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Field::create(['name' => 'Field A', 'area_id' => 1]);
        Field::create(['name' => 'Field B', 'area_id' => 1]);
        Field::create(['name' => 'Field C', 'area_id' => 1]);
        Field::create(['name' => 'Field D', 'area_id' => 1]);
        Field::create(['name' => 'Field E', 'area_id' => 2]);
        Field::create(['name' => 'Field F', 'area_id' => 2]);
        Field::create(['name' => 'Field G', 'area_id' => 3]);
        Field::create(['name' => 'Field H', 'area_id' => 3]);
        Field::create(['name' => 'Field I', 'area_id' => 3]);
        Field::create(['name' => 'Field J', 'area_id' => 4]);
        Field::create(['name' => 'Field K', 'area_id' => 4]);
        Field::create(['name' => 'Field L', 'area_id' => 4]);
    }
}
