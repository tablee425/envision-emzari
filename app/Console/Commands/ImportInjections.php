<?php

namespace Arrow\Console\Commands;

use Illuminate\Console\Command;
use Excel;
use Carbon\Carbon;
use Config;

use Arrow\Company;
use Arrow\Area;
use Arrow\Field;
use Arrow\Location;
use Arrow\Production;
use Arrow\Injection;
use Arrow\Chemical;

class ImportInjections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:injections {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import spreadsheet data to database.';

    public $dataSheet;
    public $chemical_types = [
                           '' => '',
                           'Surfactant' => '',
                           'Wax Inhibitor' => '',
                           'Wax/Asph. Inhibitor' => '',
                           'VRU' => '',
                           'De-wax' => '',
                           'De-wax CM' => '',
                           'Demulsifier' => 'demulsifier',
                           'Corrosion Inhibitor' => 'corrosion_inhibitor',
                           'Paraffin Solvent' => 'paraffin_solvent',
                           'Wax Solvent' => 'parraffin_solvent',
                           'Demulsifier/Wax' => 'demulsifier_wax',
                           'Demulsifier/Dewax' => 'demulsifier_wax',
                           'Biocide' => 'biocide',
                           'Scale inhibitor' => 'scale_inhibitor',
                           'Scale Inhibitor' => 'scale_inhibitor',
                           'Scale' => 'scale_inhibitor',
                           'Scale/Corrosion Combo' => 'scale_corrosion_combo',
                           'Scale/Corrosion Inhibitor' => 'scale_corrosion_combo',
                           'Corrosion/Scale' => 'scale_corrosion_combo',
                           'Vap Ph Corr Inh' => 'vapour_phase_corrosion_inhibitor',
                           'Iron Oxide Dissolver' => 'iron_oxide_dissolver',
                           'O2 Scavenger' => 'oxygen_scavenger',
                           'H2S Scavenger' => 'h2s_scavenger',
                           'Defoamer' => 'defoamer',
                           'Antifoam' => 'defoamer',
                           'Anti-foam' => 'defoamer',
                           'Anti Foam' => 'defoamer',
                           'Wax Dispersant' => 'wax_dispersant',
                           'Wax Dispersent' => 'wax_dispersant',
                           'Wax dispersant' => 'wax_dispersant',
                           'Wax' => 'wax_dispersant',
                           'Methanol' => 'methanol',
                           'Ethylene Glycol' => 'ethylene_glycol',
                           'Varsol' => 'varsol',
                           'Slugging Demulsifier' => 'slugging_demulsifier',
                           'Iron Control' => 'iron_control',
                           'Iron Chelator' => 'iron_control'];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        Config::set('excel.import.heading', 'slugged');
        Config::set('excel.import.startRow', 1);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->argument('filename'));
        $excel = Excel::load($this->argument('filename'), function($reader) {
            $results = $reader->all();
            // return dd(count($results));
            foreach ($results as $result)
            {
                $company = $this->_fetchCompany($result['company']);
                $area = $this->_fetchArea($result['arearun'], $company->id);
                $field = $this->_fetchField($result['fieldbattery'], $area->id);
                $location = $this->_fetchLocation($result['location'], $field->id, $result['tank_size']);
                $injection = $this->_createInjection($result, $location->id);
                $this->info('Created injection: '.$injection->id);
            }
        });

    }

    protected function _fetchCompany($name)
    {
        return Company::firstOrCreate(['name' => $name]);
    }

    protected function _fetchArea($name, $company_id)
    {
        return Area::firstOrCreate(['name' => $name, 'company_id' => $company_id]);
    }

    protected function _fetchField($name, $area_id)
    {
        return Field::firstOrCreate(['name' => $name, 'area_id' => $area_id]);
    }

    protected function _fetchLocation($name, $field_id, $capacity)
    {
        return Location::firstOrCreate(['name' => $name, 'field_id' => $field_id, 
                                        'tank_capacity' => $capacity]);
    }

    protected function _buildChemical($location_id, $name, $type, $injection)
    {
        return Chemical::firstOrCreate(['location_id' => $location_id,
                                        'name' => $name,
                                        'chemical_type' => $type,
                                        'type' => $injection]);
    }

    protected function _buildProduction($location_id, $date, $gas, $oil, $water)
    {
        $production = Production::firstOrCreate(['location_id' => $location_id,
                                          'date' => $date]);
        $production->update(['avg_gas' => $gas, 'avg_oil' => $oil, 'avg_water' => $water]);
    }

    protected function _createInjection($row, $location_id)
    {
        if ($row['gas_prod'] || $row['oil_prod'] || $row['water_prod'])
        {
            $this->_buildProduction($location_id, 
                                    $row['date']->toDateString(),
                                    $row['gas_prod'], 
                                    $row['oil_prod'], 
                                    $row['water_prod']);
        }

        if (isset($this->chemical_types[trim($row['chemical_type'])]) && $row['chemical_name'])
        {
            $this->_buildChemical($location_id, $row['chemical_name'], 
                                  $this->chemical_types[trim($row['chemical_type'])], 
                                  strtoupper($row['program']) );
        }

        return $injection = Injection::create(['location_id' => $location_id, 'type' => strtoupper($row['program']),
            'date' => $row['date'] ? $row['date']->toDateString() : '2999-12-31', 'based_on' => strtolower($row['based_on']),
            'name' => $row['chemical_name'], 'days_in_month' => $row['days_in_month'],
            'chemical_type' => $this->chemical_types[trim($row['chemical_type'])], 
            'chemical_start' =>$row['inv_start'], 'chemical_delivered' => $row['inv_delivered'], 
            'chemical_end' => $row['inv_end'],'target_ppm' => $row['target_ppm'],
            'batch_size' => $row['batch_size'], 'min_rate' => $row['min_rate'],
            'vendor_target' => $row['vendor_target'], 'unit_cost' => 100 * $row['unit_cost'],
            'comments' => $row['comments'], 'target_rate' => $row['target_rate'],
            'usage_rate' => $row['usage_rate']]);
    }



}
