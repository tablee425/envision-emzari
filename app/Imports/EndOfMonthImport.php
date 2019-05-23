<?php

namespace Arrow\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use Auth;
use Arrow\Injection;
use Arrow\Location;

class EndOfMonthImport implements ToCollection
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

        $collection->each(function($row) {
            $this->_updateEndOfMonth($row);
        });
        if(! empty($this->_notFound)) {
            $this->_request->session()->flash('missing-locations', $this->_notFound);
        }
    }

    protected function _updateEndOfMonth($row)
    {
        // Fetch locations matching name, then verify the parent field and location
        $locations = Location::where(['name' => $row[0]])->get();

        $location = $locations->first(function($location) use ($row) {
            // if(!$location->field) return dd($location);
            return ($location->field->area->company_id == Auth::user()->activeCompany()->id);
        });

        if ($location == null) {
            $this->_notFound[] = $row[0];
            return;
        }

        // Prepare date
        $months = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03',
                   'Apr' => '04', 'May' => '05', 'Jun' => '06',
                   'Jul' => '07', 'Aug' => '08', 'Sep' => '09',
                   'Oct' => '10', 'Nov' => '11', 'Dec' => '12'];
        $date = $row[2].'-'.$months[$row[1]];

        Injection::where(['name' => $row[3],'location_id' => $location->id])
            ->where(DB::raw("DATE_FORMAT(date,'%Y-%m')"), $date)
            ->update(['chemical_end' => $row[4]]);
        // $date = Carbon::createFromFormat('n/j/Y', $row[1])->format('Y-m').'-01'; // $row->date = $row[2]
    }
}
