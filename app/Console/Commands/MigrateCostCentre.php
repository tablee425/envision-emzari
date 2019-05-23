<?php

namespace Arrow\Console\Commands;

use Illuminate\Console\Command;
use Arrow\Location;

class MigrateCostCentre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:cost-centre';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate cost centre from locations to the location site.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Location::each(function($location) {
            $injection = $location->injections()->where(\DB::raw('CHAR_LENGTH(cost_centre)'), '>', 0)->first();
            if($injection)
            {
                var_dump($injection);
                $location->cost_centre = $injection->cost_centre;
                $location->save();
            }
            
        });
    }
}
