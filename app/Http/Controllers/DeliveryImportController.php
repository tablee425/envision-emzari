<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\Injection;
use Arrow\Location;
use DB;
use Excel;
use Session;

class DeliveryImportController extends Controller
{
    /**
     * Take excel sheet in, and process it with possibility of multiple
     * rows needing to be summed together based on same location and chemical.
     * Add this sum to the chemical_delivered field on that injection.
     */
    public function update(Request $request)
    {
        $file = $request->file('import');
        config(['excel.import.startRow' => 2]);
        $excel = Excel::load($file)->all();


        // Indices:
        // 0: Delivery Ticket - NOT USED
        // 1: Location
        // 2: Month
        // 3: Year
        // 4: Chemical
        // 5: Inventory Delivered (integer)
        $chemical = 4;
        $location = 1;
        $amount_delivered = 5;
        // Prepare date
        $months = ['Jan' => '01', 'Feb' => '02', 'Mar' => '03',
                   'Apr' => '04', 'May' => '05', 'Jun' => '06',
                   'Jul' => '07', 'Aug' => '08', 'Sep' => '09',
                   'Oct' => '10', 'Nov' => '11', 'Dec' => '12'];
        $date = $excel->first()[3].'-'.$months[$excel->first()[2]];

        $composition = [];
        // Group together like chemicals and locations, and sum them up.
        // End up with: $composition['location'~'chemical'] = Total of all amounts for each matching combo
        foreach($excel as $row)
        {
            $composition[$row[$location].'~'.$row[$chemical]] =
                isset($composition[$row[$location].'~'.$row[$chemical]]) ? 
                $composition[$row[$location].'~'.$row[$chemical]] + $row[$amount_delivered] :
                $row[$amount_delivered];
        }

        // Get all location id's
        $locationNames = collect(array_keys($composition))->map(function($combo) {
            return explode('~', $combo)[0];
        });
        // return dd($locationNames);
        $locationIDs = Location::whereIn('name', $locationNames)->pluck('id', 'name')->toArray();
        // Check if all location names in spreadsheet are in the DB.
        if(! $locationNames->diff(array_keys($locationIDs))->isEmpty())
        {
            return dd($locationNames->diff(array_keys($locationIDs)));
        }

        // Check for date location mismatches
        foreach($composition as $locationChemical => $amount)
        {
            // return dd($locationIDs[explode('~', $chemicalLocation)[0]]);
            if(! Injection::where(DB::raw("DATE_FORMAT(date,'%Y-%m')"), $date)
                ->where('location_id', $locationIDs[explode('~', $locationChemical)[0]])
                ->where('name', explode('~', $locationChemical)[1])
                ->first())
            {
                return dd($locationChemical. ' not found.');
            }
        }
        // Now update all the injections
        $injections = collect();
        foreach($composition as $locationChemical => $amount)
        {
            $injections->push(Injection::where(DB::raw("DATE_FORMAT(date,'%Y-%m')"), $date)
                ->where('location_id', $locationIDs[explode('~', $locationChemical)[0]])
                ->where('name', explode('~', $locationChemical)[1])
                ->first()
                ->increment('chemical_delivered', $amount));
        }
        Session::flash('upload-status', 'The spreadsheet file has been successfully imported.');
        return redirect()->back();
    }
}
