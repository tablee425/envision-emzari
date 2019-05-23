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
        $this->call(PiggingsTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(FieldsTableSeeder::class);
        // $this->call(CompaniesTableSeeder::class);
        // $this->call(LocationsTableSeeder::class);
        // $this->call(InjectionsTableSeeder::class);
        // $this->call(AreasTableSeeder::class);
        // $this->call(ProductionTableSeeder::class);
        // $this->call(ChemicalsTableSeeder::class);
    }
}
