<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Arrow\Imports\EndOfMonthImport;
use Arrow\Imports\ProductionImport;

class ImportController extends Controller
{
    public function getIndex(Request $request)
    {
        $errors = $request->session()->get('missing-locations', null);
        if($errors) {
            $request->session()->keep(['missing-locations']);
            return redirect()->action('ImportController@getErrors');
        }
        return view('import');
    }

    public function getErrors(Request $request)
    {
        $errors = $request->session()->get('missing-locations', null);
        if($errors) {
            return view('errors.import', compact('errors'));
        }
        return redirect()->action('ImportController@getIndex');
    }

    public function endOfMonthInventory(Request $request)
    {
        // For some reason I need to disable the debug bar before the
        // excel import or it breaks the javascript on the next page load.
        \Debugbar::disable();

        $file = $request->file('import');
        // config(['excel.import.startRow' => 2]);
        Excel::import(new EndOfMonthImport($request), $file);

        return redirect()->action('ImportController@getIndex');
    }

    public function production(Request $request)
    {
        // For some reason I need to disable the debug bar before the
        // excel import or it breaks the javascript on the next page load.
        \Debugbar::disable();

        $file = $request->file('import'); // ->move(storage_path().'/production-imports');
        $excel = Excel::import(new ProductionImport($request), $file); // ->all();
        return redirect()->action('ImportController@getIndex');
    }
}
