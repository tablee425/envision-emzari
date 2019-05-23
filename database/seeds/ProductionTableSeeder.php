<?php

use Illuminate\Database\Seeder;
use Arrow\Production;

class ProductionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 600; $i++)
        {
            factory(Production::class)->create();
        }
    }
}
