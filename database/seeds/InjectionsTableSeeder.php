<?php

use Illuminate\Database\Seeder;

use Arrow\Injection;

class InjectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 1500; $i++)
        {
            factory(Injection::class)->create();
        }
    }
}
