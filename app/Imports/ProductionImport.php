<?php

namespace Arrow\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use Carbon\Carbon;
use Arrow\Production;
use Arrow\Location;

class ProductionImport implements ToCollection
{
    protected $_notFound = [];
    protected $_request;

    public function __construct($request)
    {
        $this->_request = $request;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $collection->shift(); // ignore the heading row
        $collection->shift(); // ignore the heading row

        $collection->each(function($row) {
            if($row[0]) $this->_importProduction($row);
        });
        if(! empty($this->_notFound)) {
            $this->_request->session()->flash('missing-locations', $this->_notFound);
        }
    }

    public function _importProduction($row)
    {
        $searchField = auth()->user()->activeCompany()->location_import_id;
        $location = Location::where([$searchField => $row[1]])->first();

        if ($location == null) {
            $this->_notFound[] = $row[1];
            return;
        }

        // Prepare date
        $date = Carbon::createFromFormat('n/j/Y', $row[2])->format('Y-m').'-01'; // $row->date = $row[2]

        // 'location_id', 'date', 'hours_on', 'avg_oil', 'avg_gas', 'avg_water'
        $production = Production::firstOrCreate(['location_id' => $location->id, 'date' => $date]);

        $target_ppm = 0;

        $production->update(['location_id' => $location->id, 'date' => $date,
            'hours_on' => $row[3], 'avg_gas' => $row[7], 'avg_oil' => $row[8],
            'avg_water' => $row[9], 'target_ppm' => $target_ppm]);

    }
}
