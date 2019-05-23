<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Arrow\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Arrow\Production::class, function (Faker\Generator $faker) {
    return [
        'location_id' => $faker->numberBetween(1,14), 
        'date' => '2016-0'. $faker->numberBetween(1,9).'-01', 
        'hours_on' => $faker->numberBetween(180,390), 
        'avg_oil' => $faker->randomFloat(2,1,80),
        'avg_gas' => $faker->randomFloat(2,1,80), 
        'avg_water' => $faker->randomFloat(2,1,80)
    ];
});

$factory->define(Arrow\Injection::class, function (Faker\Generator $faker) {
    $based_on = ['oil', 'gas', 'water'];
    $chemical_types = ['corrosion_inhibitor', 'demulsifier'];
    $type = ['CONTINUOUS', 'BATCH'];
    $chemical_start = $faker->numberBetween(20, 600);
    return [
        'location_id' => $faker->numberBetween(1,14),
        'name' => $faker->colorName,
        'days_in_month' => $faker->numberBetween(15,31),
        'based_on' => $based_on[$faker->numberBetween(0,2)],
        'target_frequency' => $faker->numberBetween(10, 28),
        'target_ppm' => $faker->numberBetween(50, 300),
        'chemical_type' => $chemical_types[$faker->numberBetween(0,1)],
        'chemical_start' => $chemical_start,
        'chemical_end' => ($chemical_start - $faker->numberBetween(20, $chemical_start)),
        'chemical_delivered' => $faker->numberBetween(30, 200),
        'batch_size' => $faker->numberBetween(300,500),
        'scheduled_batches' => $faker->numberBetween(10,28),
        'circulation_time' => $faker->numberBetween(100,200) * 0.01,
        'diluent_required' => $faker->numberBetween(100,5000) * 0.01,
        'date' => '2016-0'.$faker->numberBetween(1,9).'-01',
        'unit_cost' => $faker->numberBetween(2000,100000),
        'min_rate' => $faker->numberBetween(80,400),
        'vendor_target' => $faker->numberBetween(100, 800),
        'type' => $type[$faker->numberBetween(0,1)],
    ];
});