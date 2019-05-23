<?php

use Illuminate\Database\Seeder;
use Arrow\Company;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create(['name' => 'ABC Company']);
        Company::create(['name' => 'DEF Company']);
        Company::create(['name' => 'GHI Company']);
        Company::create(['name' => 'JKL Company']);
    }
}
