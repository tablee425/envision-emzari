<?php
namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Arrow\Field;
use DB;
use Carbon\Carbon;
use Arrow\Exports\InjectionsExport;

use Arrow\Http\Requests;

class ExcelReportController extends Controller
{
    public function getIndex()
    {
        $fields = auth()->user()->activeCompany()->fields;
        return view('reports.excel.index', compact('fields'));
    }

    public function postReport(Request $request)
    {
        $field = Field::find($request->field_id);
        $prior_year = (int)$request->prior_year;

        return (new InjectionsExport($field, $prior_year))->download("$field->name.xlsx");
    }

    public function postReport2(Request $request)
    {
        // Authenticate the field here. Make sure company is permitted
        // to see it.
        $field = Field::find($request->field_id);
    	$sheets = Excel::create($field->name, function($excel) use ($field, $request) {
            if(!(int)$request->prior_year)
            {
                // return dd('current year.');
                $date = new Carbon('first day of January '.date('Y'), 'America/Vancouver');
                $untilDate = Carbon::now()->subMonth();
            } else {
                // return dd('prior year.');
                $date = new Carbon('first day of January '.(date('Y') - 1), 'America/Vancouver');
                $untilDate = new Carbon('first day of January '.date('Y'), 'America/Vancouver');
            }
            $oldDate = $date;
            while($date < $untilDate)
            {
                $injections = $field->injections()
                    ->join('production', function($join) {
                        $join->on('production.location_id', '=', 'injections.location_id');
                        $join->on(DB::raw("DATE_FORMAT(production.date, '%Y-%m')"),'=', DB::raw("DATE_FORMAT(injections.date, '%Y-%m')"));
                    })
                    ->where('type','CONTINUOUS')
                    ->where( DB::raw('MONTH(date)'), $date->month )
                    ->where( DB::raw('YEAR(date)'), $date->year )
                    ->select('injections.*', 'production.id as production_id')
                    ->get();
                $injections->load('production');
                return dd($injections);
                if (! $injections->isEmpty())
                {
                    $excel->sheet($date->format('F Y'), function($sheet) use ($field, $injections, $date) {
                        $sheet->loadView('reports.excel.field', compact('injections', 'field','date'))
            				  ->setAutoSize(true)
                              ->mergeCells('A1:D3')
            				  ->mergeCells('E1:Q3')
            				  ->mergeCells('A4:Q4')
            				  ->mergeCells('A5:Q5')
                              // ->setWidth('A',5)
                              ->setColumnFormat(array(
                                    'M' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    'N' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    'O' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    'P' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                 )
                              );
                    });
                }
                $oldDate = $date;
                $date = $date->addMonth();
            }
    	})->export('xls');
    }
}
