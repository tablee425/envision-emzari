<?php

use Illuminate\Database\Seeder;

use Arrow\Area;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create(['name' => 'Area I', 'company_id' => 1]);
        Area::create(['name' => 'Area II', 'company_id' => 1]);
        Area::create(['name' => 'Area III', 'company_id' => 1]);
        Area::create(['name' => 'Area IV', 'company_id' => 2]);
        Area::create(['name' => 'Area V', 'company_id' => 2]);
    }
}
