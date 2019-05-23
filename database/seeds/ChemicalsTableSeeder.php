<?php

use Illuminate\Database\Seeder;
use Arrow\Chemical;

class ChemicalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Chemical::create(['location_id' => 1, 'name' => 'Chemical A', 'chemical_type' => 'demulsifier', 'type' => "CONTINUOUS"]);
        Chemical::create(['location_id' => 1, 'name' => 'Chemical B', 'chemical_type' => 'corrosion_inhibitor', 'type' => "BOTH"]);
        Chemical::create(['location_id' => 1, 'name' => 'Chemical C', 'chemical_type' => 'corrosion_inhibitor', 'type' => "BATCH"]);
        Chemical::create(['location_id' => 1, 'name' => 'Chemical D', 'chemical_type' => 'demulsifier', 'type' => "CONTINUOUS"]);
        Chemical::create(['location_id' => 1, 'name' => 'Chemical E', 'chemical_type' => 'demulsifier', 'type' => "BOTH"]);
        Chemical::create(['location_id' => 2, 'name' => 'Chemical F', 'chemical_type' => 'demulsifier', 'type' => "BATCH"]);
        Chemical::create(['location_id' => 2, 'name' => 'Chemical G', 'chemical_type' => 'corrosion_inhibitor', 'type' => "CONTINUOUS"]);
        Chemical::create(['location_id' => 2, 'name' => 'Chemical H', 'chemical_type' => 'demulsifier', 'type' => "CONTINUOUS"]);
        Chemical::create(['location_id' => 2, 'name' => 'Chemical I', 'chemical_type' => 'corrosion_inhibitor', 'type' => "BOTH"]);
        Chemical::create(['location_id' => 3, 'name' => 'Chemical J', 'chemical_type' => 'demulsifier', 'type' => "BATCH"]);
        Chemical::create(['location_id' => 3, 'name' => 'Chemical K', 'chemical_type' => 'corrosion_inhibitor', 'type' => "CONTINUOUS"]);
        Chemical::create(['location_id' => 3, 'name' => 'Chemical L', 'chemical_type' => 'demulsifier', 'type' => "BATCH"]);
        Chemical::create(['location_id' => 4, 'name' => 'Chemical M', 'chemical_type' => 'demulsifier', 'type' => "BOTH"]);
        Chemical::create(['location_id' => 4, 'name' => 'Chemical N', 'chemical_type' => 'demulsifier', 'type' => "CONTINUOUS"]);
        Chemical::create(['location_id' => 4, 'name' => 'Chemical O', 'chemical_type' => 'corrosion_inhibitor', 'type' => "BATCH"]);

    }
}
