<?php

use Illuminate\Database\Seeder;

use Arrow\Pigging;

class PiggingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pigging::create(['location_id' => , 'od' => , 'license' => , 'frequency' => ,
        //     'scheduled_on' => , 'shipped_on' => , 'cancelled_on' => , 'pig_size' => ,
        //     'pig_number' => , 'corr_inh_vol' => , 'biocide_vol' => , 'water_vol' => ,
        //     'field_operator' => , 'comments' => ]);
        Pigging::create(['location_id' => 1521, 'od' => 88.9, 'license' => '123456', 
            'frequency' => 7, 'scheduled_on' => '2016-11-21', 'pig_size' => 6,
            'field_operator' => 'JB']);
        Pigging::create(['location_id' => 1522, 'od' => 88.9, 'license' => '123456', 
            'frequency' => 7, 'scheduled_on' => '2016-11-21', 'pig_size' => 6,
            'field_operator' => 'JB']);
        Pigging::create(['location_id' => 1523, 'od' => 88.9, 'license' => '123456', 
            'frequency' => 7, 'scheduled_on' => '2016-11-21', 'pig_size' => 6,
            'field_operator' => 'JB']);
        Pigging::create(['location_id' => 1521, 'od' => 88.9, 'license' => '123456', 'comments' => 'Successful run',
            'frequency' => 7, 'scheduled_on' => '2016-11-21', 'shipped_on' => '2016-11-14', 'pig_size' => 6,
            'field_operator' => 'JB', 'pig_number' => 'A24', 'corr_inh_vol' => 12, 'water_vol' => 48]);
        Pigging::create(['location_id' => 1522, 'od' => 88.9, 'license' => '123456', 'comments' => 'Successful run',
            'frequency' => 7, 'scheduled_on' => '2016-11-21', 'shipped_on' => '2016-11-14','pig_size' => 6,
            'field_operator' => 'JB', 'pig_number' => 'A24', 'corr_inh_vol' => 12, 'water_vol' => 48]);
        Pigging::create(['location_id' => 1523, 'od' => 88.9, 'license' => '123456', 'comments' => 'Successful run',
            'frequency' => 7, 'scheduled_on' => '2016-11-21', 'shipped_on' => '2016-11-14','pig_size' => 6,
            'field_operator' => 'JB', 'pig_number' => 'A24', 'corr_inh_vol' => 12, 'water_vol' => 48]);
    
        Pigging::create(['location_id' => 1525, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1526, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1527, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1528, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1529, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1530, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1531, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1532, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-23', 'pig_size' => 4,
            'field_operator' => 'MJ']);

        Pigging::create(['location_id' => 1525, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1526, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1527, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1528, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1529, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1530, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1531, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);
        Pigging::create(['location_id' => 1532, 'od' => 114.3, 'license' => '456789', 
            'frequency' => 14, 'scheduled_on' => '2016-11-09', 'shipped_on' => '2016-11-10', 
            'pig_size' => 4, 'pulled_on' => '2016-11-10', 'pig_number' => 'B12', 'corr_inh_vol' => 20, 
            'biocide_vol' => 20, 'water_vol' => 200, 'comments' => 'Increased frequency.',
            'field_operator' => 'MJ']);

        Pigging::create(['location_id' => 1533, 'od' => 88.9, 'license' => '876543', 'frequency' => 30,
            'scheduled_on' => '2016-11-18', 'cancelled_on' => '2016-11-18', 'pig_size' => 6,
            'field_operator' => 'JB', 'comments' => 'Pig run skipped, approved by Udell.']);
        Pigging::create(['location_id' => 1534, 'od' => 88.9, 'license' => '876543', 'frequency' => 30,
            'scheduled_on' => '2016-11-18', 'cancelled_on' => '2016-11-18', 'pig_size' => 6,
            'field_operator' => 'JB', 'comments' => 'Pig run skipped, approved by Udell.']);
        Pigging::create(['location_id' => 1535, 'od' => 88.9, 'license' => '876543', 'frequency' => 30,
            'scheduled_on' => '2016-11-18', 'cancelled_on' => '2016-11-18', 'pig_size' => 6,
            'field_operator' => 'JB', 'comments' => 'Pig run skipped, approved by Udell.']);
        Pigging::create(['location_id' => 1536, 'od' => 88.9, 'license' => '876543', 'frequency' => 30,
            'scheduled_on' => '2016-11-18', 'cancelled_on' => '2016-11-18', 'pig_size' => 6,
            'field_operator' => 'JB', 'comments' => 'Pig run skipped, approved by Udell.']);
    }
}
